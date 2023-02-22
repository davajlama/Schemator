<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi;

use Davajlama\Schemator\JsonSchema\JsonSchemaBuilder;
use Davajlama\Schemator\Schema\Schema;
use LogicException;

use function array_search;
use function array_walk_recursive;
use function count;
use function in_array;
use function is_array;
use function reset;

final class OpenApiBuilder
{
    private JsonSchemaBuilder $jsonSchemaBuilder;

    /**
     * @var Schema[]
     */
    private array $schemas = [];

    public function __construct(JsonSchemaBuilder $jsonSchemaBuilder)
    {
        $this->jsonSchemaBuilder = $jsonSchemaBuilder;
    }

    /**
     * @return mixed[]
     */
    public function build(Api $api): array
    {
        $data = $api->build();

        array_walk_recursive($data, function (&$value): void {
            if ($value instanceof Schema) {
                $ref = $this->resolveSchemaName($value);
                $this->schemas[$ref] = $value;
                $value = $this->generateSchemaReference($ref);
            }
        });

        $data['components'] = $this->createComponent();

        return $data;
    }

    private function resolveSchemaName(Schema $schema): string
    {
        return $schema->getName() ?? $schema::class;
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
        foreach ($this->schemas as $schemaClass => $schema) {
            $data = $this->jsonSchemaBuilder->build($schema);
            unset($data['$schema']);

            $this->arrayWalkRecursive($data, '', static function (&$value, $key, &$parent, $context): void {
                if ($context !== 'properties' && $key === 'type' && is_array($value)) {
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
    private function arrayWalkRecursive(array &$array, string $context, callable $callback): void
    {
        foreach ($array as $key => &$value) {
            $callback($value, $key, $array, $context);
            if (is_array($value)) {
                $this->arrayWalkRecursive($value, $key, $callback);
            }
        }
    }

    private function generateSchemaReference(string $class): string
    {
        return '#/components/schemas/' . $class;
    }
}
