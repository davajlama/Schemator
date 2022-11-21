<?php

declare(strict_types=1);

namespace Davajlama\JsonSchemaGenerator;

use Davajlama\JsonSchemaGenerator\Resolver\ResolverInterface;
use Davajlama\JsonSchemaGenerator\Resolver\TypeResolver;
use Davajlama\Schemator\Schema;
use LogicException;

use function json_encode;
use function sprintf;

final class SchemaGenerator
{
    private bool $throwOnUnresolvedRule = true;

    /**
     * @var ResolverInterface[]
     */
    private array $ruleResolvers = [];

    public function __construct()
    {
        $this->ruleResolvers[] = new TypeResolver();
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
                    $resolved = false;
                    foreach ($this->ruleResolvers as $resolver) {
                        if ($resolver->support($rule)) {
                            $resolved = true;
                            $resolver->resolve($definition, $rule);
                        }
                    }

                    if ($this->throwOnUnresolvedRule && $resolved === false) {
                        throw new LogicException(sprintf('No resolver for %s.', $rule::class));
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

    public function setThrowOnUnresolvedRule(bool $throwOnUnresolvedRule): SchemaGenerator
    {
        $this->throwOnUnresolvedRule = $throwOnUnresolvedRule;

        return $this;
    }
}
