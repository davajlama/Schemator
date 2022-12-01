<?php

declare(strict_types=1);

namespace Davajlama\JsonSchemaGenerator\Resolver;

use Davajlama\JsonSchemaGenerator\Definition;
use Davajlama\JsonSchemaGenerator\ReflectionExtractor;
use Davajlama\JsonSchemaGenerator\SchemaGenerator;
use Davajlama\Schemator\RuleInterface;
use Davajlama\Schemator\Rules\ArrayOf;
use Davajlama\Schemator\Schema;

final class ArrayOfResolver implements ResolverInterface, SchemaGeneratorAwareInterface
{
    private SchemaGenerator $schemaGenerator;

    public function setSchemaGenerator(SchemaGenerator $schemaGenerator): void
    {
        $this->schemaGenerator = $schemaGenerator;
    }

    public function support(RuleInterface $rule): bool
    {
        return $rule instanceof ArrayOf;
    }

    public function resolve(Definition $definition, RuleInterface $rule): void
    {
        $itemDefinition = new Definition();

        $schema = ReflectionExtractor::getProperty($rule, 'schema');
        $this->schemaGenerator->generateFromSchema($schema, $itemDefinition);

        $definition->addType('array');
        $definition->setItems($itemDefinition->build());
    }
}
