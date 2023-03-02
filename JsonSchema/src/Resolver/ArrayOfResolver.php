<?php

declare(strict_types=1);

namespace Davajlama\Schemator\JsonSchema\Resolver;

use Davajlama\Schemator\JsonSchema\Definition;
use Davajlama\Schemator\JsonSchema\JsonSchemaBuilder;
use Davajlama\Schemator\JsonSchema\ReflectionExtractor;
use Davajlama\Schemator\Schema\RuleInterface;
use Davajlama\Schemator\Schema\Rules\ArrayOf;
use Davajlama\Schemator\Schema\Schema;
use Davajlama\Schemator\Schema\SchemaFactoryAware;
use Davajlama\Schemator\Schema\SchemaFactoryAwareInterface;

final class ArrayOfResolver implements ResolverInterface, SchemaGeneratorAwareInterface, SchemaFactoryAwareInterface
{
    use SchemaFactoryAware;

    private JsonSchemaBuilder $schemaGenerator;

    public function setSchemaGenerator(JsonSchemaBuilder $schemaGenerator): void
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

        /** @var Schema $schema */
        $schema = ReflectionExtractor::getProperty($rule, 'schema');
        $this->schemaGenerator->generateFromSchema($this->getSchemaFactory()->create($schema), $itemDefinition);

        $definition->addType('array');
        $definition->setItems($itemDefinition);
    }
}
