<?php

declare(strict_types=1);

namespace Davajlama\JsonSchemaGenerator\Resolver;

use Davajlama\JsonSchemaGenerator\Definition;
use Davajlama\JsonSchemaGenerator\SchemaGenerator;
use Davajlama\Schemator\RuleInterface;
use Davajlama\Schemator\Rules\Type\ArrayOfStringType;
use Davajlama\Schemator\Rules\Type\ArrayType;
use Davajlama\Schemator\Rules\Type\BoolType;
use Davajlama\Schemator\Rules\Type\FloatType;
use Davajlama\Schemator\Rules\Type\IntegerType;
use Davajlama\Schemator\Rules\Type\StringType;
use Exception;

final class TypeResolver implements ResolverInterface
{
    public function support(RuleInterface $rule): bool
    {
        return $rule instanceof StringType
            || $rule instanceof FloatType
            || $rule instanceof ArrayType
            || $rule instanceof BoolType
            || $rule instanceof IntegerType
            || $rule instanceof ArrayOfStringType;
    }

    public function resolve(Definition $definition, RuleInterface $rule): void
    {
        if ($rule instanceof StringType) {
            $definition->addType('string');
        } else if ($rule instanceof FloatType) {
            $definition->addType('number');
        } else if ($rule instanceof ArrayType) {
            $definition->addType('array');
        } else if ($rule instanceof  BoolType) {
            $definition->addType('boolean');
        } else if ($rule instanceof IntegerType) {
            $definition->addType('integer');
        } else if ($rule instanceof ArrayOfStringType) {
            $stringDefinition = new Definition();
            $stringDefinition->addType('string');

            $definition->addType('array');
            $definition->setItems($stringDefinition);
        }
    }
}
