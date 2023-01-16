<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi;

use Davajlama\Schemator\JsonSchema\JsonSchemaBuilder;
use Davajlama\Schemator\Schema\Schema;
use LogicException;
use Symfony\Component\Yaml\Tag\TaggedValue;
use Symfony\Component\Yaml\Yaml;

use function array_search;
use function array_unique;
use function array_walk_recursive;
use function count;
use function dirname;
use function file_get_contents;
use function gettype;
use function in_array;
use function is_array;
use function is_string;
use function reset;
use function sprintf;

final class OpenApiBuilder
{
    private const TAG_SCHEMA = 'schema';
    private const TAG_INCLUDE = 'include';
    private const TAG_IMPORT_STRING = 'import_string';

    /**
     * @var string[]
     */
    private array $schemas = [];

    /**
     * @var SchemaLoaderInterface[]
     */
    private array $schemaLoaders = [];

    public function __construct()
    {
        $this->schemaLoaders[] = new BaseSchemaLoader();
    }

    public function addSchemaLoader(SchemaLoaderInterface $loader): self
    {
        $this->schemaLoaders[] = $loader;

        return $this;
    }

    public function build(Api $api): array
    {
        $data = $api->build();

        array_walk_recursive($data, function (&$value): void {
            if ($value instanceof Schema) {
                $this->schemas[] = get_class($value);
                $value = $this->generateSchemaReference(get_class($value));
            }
        });

        $data['components'] = $this->createComponent();

        return $data;
    }

    /**
     * @return mixed[]
     */
    private function createComponent(): array
    {
        return [
            'schemas' => $this->createComponentSchema(),
        ];
    }

    /**
     * @return mixed[]
     */
    private function createComponentSchema(): array
    {
        $list = [];
        foreach (array_unique($this->schemas) as $schemaClass) {
            $schema = $this->loadSchema($schemaClass);

            $generator = new JsonSchemaBuilder();
            $data = $generator->build($schema);
            unset($data['$schema']);

            $this->arrayWalkRecursive($data, static function (&$value, $key, &$parent): void {
                if ($key === 'type' && is_array($value)) {
                    if (in_array('null', $value, true)) {
                        $parent['nullable'] = true;
                        unset($value[array_search('null', $value, true)]);
                    }

                    if (count($value) > 1) {
                        throw new LogicException('Multiple types not supported.');
                    }

                    $value = reset($value);
                }
            });

            $list[$schemaClass] = $data;
        }

        return $list;
    }

    /**
     * @param mixed[] $array
     */
    private function arrayWalkRecursive(array &$array, callable $callback): void
    {
        foreach ($array as $key => &$value) {
            $callback($value, $key, $array);
            if (is_array($value)) {
                $this->arrayWalkRecursive($value, $callback);
            }
        }
    }

    private function loadSchema(string $class): Schema
    {
        foreach ($this->schemaLoaders as $loader) {
            $schema = $loader->resolve($class);
            if ($schema !== null) {
                return $schema;
            }
        }

        throw new LogicException(sprintf('Schema %s could not be loaded.', $class));
    }

    private function generateSchemaReference(string $class): string
    {
        return '#/components/schemas/' . $class;
    }
}
