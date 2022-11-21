<?php

declare(strict_types=1);

namespace Davajlama\JsonSchemaGenerator;

use Davajlama\JsonSchemaGenerator\Resolver\ResolverInterface;
use Davajlama\JsonSchemaGenerator\Resolver\StringResolver;
use Davajlama\Schemator\Schema;

use function json_encode;

final class SchemaGenerator
{
    /**
     * @var ResolverInterface[]
     */
    private array $ruleResolvers = [];

    public function __construct()
    {
        $this->ruleResolvers[] = new StringResolver();
    }

    public function generate(Schema $schema): string
    {
        $sch = new \Davajlama\JsonSchemaGenerator\Schema();

        $this->generateFromSchema($schema, $sch);

        $data = $sch->build();

        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
    }

    protected function generateFromSchema(Schema $schema, Definition $def): void
    {
        $def->setAdditionalProperties($schema->isAdditionalPropertiesAllowed());

        foreach ($schema->getProperties() as $name => $property) {
            $definition = new Definition();

            if ($property->getReference() === null) {
                foreach ($property->getRules() as $rule) {
                    foreach ($this->ruleResolvers as $resolver) {
                        if ($resolver->support($rule)) {
                            $resolver->resolve($definition, $rule);
                        }
                    }
                }

                if ($property->isNullable()) {
                    $definition->addType('null');
                }

                if ($property->getTitle() !== null) {
                    $definition->setTitle($property->getTitle());
                }

                if ($property->getDescription() !== null) {
                    $definition->setDescription($property->getDescription());
                }

                if ($property->getExamples() !== null) {
                    $definition->setExamples($property->getExamples());
                }
            } else {
                $this->generateFromSchema($property->getReference(), $definition);
            }

            $def->addProperty($name, $definition, $property->isRequired());
        }
    }
}
