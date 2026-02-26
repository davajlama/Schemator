<?php

declare(strict_types=1);

namespace Davajlama\Schemator\JsonSchema\Resolver;

use Davajlama\Schemator\JsonSchema\Definition;
use Davajlama\Schemator\Schema\RuleInterface;
use Davajlama\Schemator\Schema\Rules\Type\ArrayOfIntegerType;
use Davajlama\Schemator\Schema\Rules\Type\ArrayOfStringType;
use Davajlama\Schemator\Schema\Rules\Type\ArrayType;
use Davajlama\Schemator\Schema\Rules\Type\BoolType;
use Davajlama\Schemator\Schema\Rules\Type\FloatType;
use Davajlama\Schemator\Schema\Rules\Type\IntegerType;
use Davajlama\Schemator\Schema\Rules\Type\NumberType;
use Davajlama\Schemator\Schema\Rules\Type\StringType;

final class TypeResolver implements ResolverInterface
{
    public function support(RuleInterface $rule): bool
    {
        return $rule instanceof StringType
            || $rule instanceof NumberType
            || $rule instanceof FloatType
            || $rule instanceof ArrayType
            || $rule instanceof BoolType
            || $rule instanceof IntegerType
            || $rule instanceof ArrayOfStringType
            || $rule instanceof ArrayOfIntegerType;
    }

    public function resolve(Definition $definition, RuleInterface $rule): void
    {
        if ($rule instanceof StringType) {
            $definition->addType('string');
        } elseif ($rule instanceof NumberType) {
            $definition->addType('number');
        } elseif ($rule instanceof FloatType) {
            $definition->addType('number');
        } elseif ($rule instanceof ArrayType) {
            $definition->addType('array');
        } elseif ($rule instanceof BoolType) {
            $definition->addType('boolean');
        } elseif ($rule instanceof IntegerType) {
            $definition->addType('integer');
        } elseif ($rule instanceof ArrayOfStringType) {
            $stringDefinition = new Definition();
            $stringDefinition->addType('string');

            $definition->addType('array');
            $definition->setItems($stringDefinition);
        } elseif ($rule instanceof ArrayOfIntegerType) {
            $integerDefinition = new Definition();
            $integerDefinition->addType('integer');

            $definition->addType('array');
            $definition->setItems($integerDefinition);
        }
    }
}
