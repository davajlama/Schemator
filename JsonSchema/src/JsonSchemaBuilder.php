<?php

declare(strict_types=1);

namespace Davajlama\Schemator\JsonSchema;

use Davajlama\Schemator\JsonSchema\Resolver\ArrayOfResolver;
use Davajlama\Schemator\JsonSchema\Resolver\ArrayOfValuesResolver;
use Davajlama\Schemator\JsonSchema\Resolver\DateTimeResolver;
use Davajlama\Schemator\JsonSchema\Resolver\DynamicObjectResolver;
use Davajlama\Schemator\JsonSchema\Resolver\EnumResolver;
use Davajlama\Schemator\JsonSchema\Resolver\FormatResolver;
use Davajlama\Schemator\JsonSchema\Resolver\ItemsResolver;
use Davajlama\Schemator\JsonSchema\Resolver\LengthResolver;
use Davajlama\Schemator\JsonSchema\Resolver\RangeResolver;
use Davajlama\Schemator\JsonSchema\Resolver\ResolverInterface;
use Davajlama\Schemator\JsonSchema\Resolver\SchemaGeneratorAwareInterface;
use Davajlama\Schemator\JsonSchema\Resolver\TypeResolver;
use Davajlama\Schemator\Schema\Schema;
use Davajlama\Schemator\Schema\SchemaFactory;
use Davajlama\Schemator\Schema\SchemaFactoryAwareInterface;
use Davajlama\Schemator\Schema\SchemaFactoryInterface;
use LogicException;

use function json_encode;
use function sprintf;

final class JsonSchemaBuilder
{
    private SchemaFactoryInterface $schemaFactory;

    private bool $throwOnUnresolvedRule = true;

    /**
     * @var ResolverInterface[]
     */
    private array $ruleResolvers = [];

    public function __construct(?SchemaFactoryInterface $schemaFactory = null)
    {
        $this->schemaFactory = $schemaFactory ?? new SchemaFactory();

        $this->ruleResolvers[] = new TypeResolver();
        $this->ruleResolvers[] = new ArrayOfResolver();
        $this->ruleResolvers[] = new EnumResolver();
        $this->ruleResolvers[] = new RangeResolver();
        $this->ruleResolvers[] = new DateTimeResolver();
        $this->ruleResolvers[] = new LengthResolver();
        $this->ruleResolvers[] = new ItemsResolver();
        $this->ruleResolvers[] = new FormatResolver();
        $this->ruleResolvers[] = new DynamicObjectResolver();
        $this->ruleResolvers[] = new ArrayOfValuesResolver();
    }

    public function addResolver(ResolverInterface $resolver): self
    {
        $this->ruleResolvers[] = $resolver;

        return $this;
    }

    public function buildToJson(Schema $schema): string
    {
        return json_encode($this->build($schema), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
    }

    /**
     * @return mixed[]
     */
    public function build(Schema $schema): array
    {
        $sch = new SchemaDefinition();

        $this->generateFromSchema($schema, $sch);

        return $sch->build();
    }

    public function generateFromSchema(Schema $schema, Definition $def): void
    {
        $def->setAdditionalProperties($schema->isAdditionalPropertiesAllowed());

        foreach ($schema->getProperties() as $name => $property) {
            $definition = new Definition();

            if ($property->getReference() === null) {
                foreach ($property->getRules() as $rule) {
                    $resolved = false;
                    foreach ($this->ruleResolvers as $resolver) {
                        if ($resolver instanceof SchemaGeneratorAwareInterface) {
                            $resolver->setSchemaGenerator($this);
                        }

                        if ($resolver instanceof SchemaFactoryAwareInterface) {
                            $resolver->setSchemaFactory($this->schemaFactory);
                        }

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
                $this->generateFromSchema($this->schemaFactory->create($property->getReference()), $definition);
            }

            $def->addProperty($name, $definition, $property->isRequired());
        }
    }

    public function setThrowOnUnresolvedRule(bool $throwOnUnresolvedRule): JsonSchemaBuilder
    {
        $this->throwOnUnresolvedRule = $throwOnUnresolvedRule;

        return $this;
    }
}
