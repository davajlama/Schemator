<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi;

use Davajlama\JsonSchemaGenerator\SchemaGenerator;
use Davajlama\Schemator\Schema;
use LogicException;
use Symfony\Component\Yaml\Tag\TaggedValue;
use Symfony\Component\Yaml\Yaml;

use function array_unique;
use function array_walk_recursive;
use function class_exists;
use function gettype;
use function is_array;
use function is_string;
use function sprintf;

final class OpenApiBuilder
{
    private const TAG_SCHEMA = 'schema';

    private array $schemas = [];

    public function parse(string $content): string
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

        return Yaml::dump($data, 512, 2);
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

            if (!class_exists($schemaClass)) {
                throw new LogicException(sprintf('Schema class %s not exists.', $schemaClass));
            }

            $schema = new $schemaClass();
            if ($schema instanceof Schema === false) {
                throw new LogicException(sprintf('Schema object must be instance of %s.', Schema::class));
            }

            $generator = new SchemaGenerator();
            $data = $generator->build($schema);
            unset($data['$schema']);
            $list[$schemaClass] = $data;

            //var_dump($foo);
        }

        return $list;
    }

    private function generateSchemaReference(string $class): string
    {
        return '#/components/schemas/' . $class;
    }
}
