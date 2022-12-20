<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi;

use Davajlama\JsonSchemaGenerator\SchemaGenerator;
use Davajlama\Schemator\Schema;
use Exception;
use LogicException;
use Symfony\Component\Yaml\Tag\TaggedValue;
use Symfony\Component\Yaml\Yaml;

use function array_search;
use function array_unique;
use function array_walk_recursive;
use function count;
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

    public function build(string $content): string
    {
        $data = $this->buildArray($content);

        return Yaml::dump($data, 512, 2);
    }

    public function buildFromFile(string $file): string
    {
        return $this->build(file_get_contents($file));
    }

    public function buildArrayFromFile(string $file): array
    {
        return $this->buildArray(file_get_contents($file));
    }

    public function buildArray(string $content): array
    {
        $data = Yaml::parse($content, Yaml::PARSE_CUSTOM_TAGS);

        if (!is_array($data)) {
            throw new LogicException(sprintf('Parsed result must be an array, %s given.', gettype($data)));
        }

        array_walk_recursive($data, function (&$value): void {
            if ($value instanceof TaggedValue) {
                switch ($value->getTag()) {
                    case self::TAG_SCHEMA:
                        $this->schemas[] = $value->getValue();
                        $value = $this->generateSchemaReference($value->getValue());
                        break;
                    default:
                        throw new LogicException(sprintf('Unsupported tag %s.', $value->getTag()));
                }
            }
        });

        $data['components'] = $this->createComponent();

        //var_dump($data);exit;
        return $data;
    }

    private function createComponent(): array
    {
        return [
            'schemas' => $this->createComponentSchema(),
        ];
    }

    private function createComponentSchema(): array
    {
        $list = [];
        foreach (array_unique($this->schemas) as $schemaClass) {
            if (!is_string($schemaClass)) {
                throw new LogicException(sprintf('Schema class name must be a string, %s given.', gettype($schemaClass)));
            }

            $schema = $this->loadSchema($schemaClass);

            $generator = new SchemaGenerator();
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
