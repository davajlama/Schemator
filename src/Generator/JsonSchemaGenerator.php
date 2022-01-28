<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Generator;

use Davajlama\Schemator\Rules\IntegerType;
use Davajlama\Schemator\Rules\NonEmptyStringRule;
use Davajlama\Schemator\Rules\NullableInteger;
use Davajlama\Schemator\Rules\NullableString;
use Davajlama\Schemator\Rules\StringTypeRule;
use Davajlama\Schemator\Schema;

class JsonSchemaGenerator
{
    private Schema $schema;

    private string $schemaUrl = 'https://json-schema.org/draft-07/schema#';

    private string $schemaType = 'object';

    private array $definitions = [];

    public function __construct(Schema $schema)
    {
        $this->schema = $schema;
    }

    public function generate(): array
    {
        $header = [
            '$schema' => $this->schemaUrl,
            'type' => $this->schemaType,
            'title' => $this->schema->getTitle(),
        ];

        $body = $this->_generate($this->schema);

        $footer = [];
        if(count($this->definitions) > 0) {
            $footer = [
                'definitions' => $this->definitions,
            ];
        }

        $full = array_merge($header, $body, $footer);
        return $full;
    }

    protected function _generate(Schema $schema, ?string $definitionName = null): array
    {
        $required = [];
        $properties = [];

        foreach($schema->getProperties() as $name => $property) {
            if($property->isHidden()) {
                continue;
            }

            if($property->isRequired()) {
                $required[] = $name;
            }

            $properties[$name] = $this->buildProperty($property, $name);
        }

        $data = [];
        $data['type'] = $this->schemaType;

        if($definitionName !== null) {
            $data['$id'] = '#/definitions/' . $definitionName;
        }

        $data['title'] = $schema->getTitle();
        $data['additionalProperties'] = $schema->isAdditionalPropertiesAllowed();
        $data['required'] = $required;
        $data['properties'] = $properties;

        return $data;
    }

    protected function getMinLength(array $rules): ?int
    {
        $min = null;
        foreach($rules as $rule) {
            switch (get_class($rule)) {
                case NonEmptyStringRule::class:
                    $min = 1;
                    break;
            }
        }

        return $min;
    }

    protected function getTypes(array $rules): array
    {
        $types = [];
        foreach($rules as $rule) {
            $classList = class_parents($rule);
            $classList[] = get_class($rule);
            foreach ($classList as $class) {
                switch ($class) {
                    case NullableInteger::class:
                    case NullableString::class:
                        $types[] = 'null';
                        break;
                    case IntegerType::class:
                        $types[] = 'integer';
                        break;
                    case StringTypeRule::class:
                        $types[] = 'string';
                        break;
                }
            }

        }

        return array_unique($types);
    }

    protected function buildProperty(Schema\SchemaProperty $property, string $name): array
    {
        if($property->isDefinition()) {
            $definition = $this->schema->definition($name);
            $definitionName = $definition->getName() ?: $name;
            $this->buildDefinition($definition, $definitionName);
            return [
                '$ref' => '#/definitions/' . $definitionName,
            ];
        } else {
            $body = [
                '$id' => '#/properties/' . $name,
            ];

            $types = $this->getTypes($property->getRules());
            if($types) {
                $type = count($types) === 1 ? reset($types) : $types;
                $body['type'] = $type;
            }

            $minLength = $this->getMinLength($property->getRules());
            if($minLength !== null) {
                $body['minLength'] = $minLength;
            }

            if($property->getTitle() !== null) {
                $body['title'] = $property->getTitle();
            }

            if(count($property->getExamples()) > 0) {
                $body['examples'] = $property->getExamples();
            }

            return $body;
        }
    }

    protected function buildDefinition(Schema $schema, string $name): void
    {
        $this->definitions[$name] = $this->_generate($schema, $name);
    }
}