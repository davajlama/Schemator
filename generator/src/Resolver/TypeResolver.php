<?php

declare(strict_types=1);

namespace Davajlama\JsonSchemaGenerator\Resolver;

use Davajlama\JsonSchemaGenerator\Definition;
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
        switch ($rule::class) {
            case StringType::class:
                $definition->addType('string');
                break;

            case FloatType::class:
                $definition->addType('number');
                break;

            case ArrayType::class:
                $definition->addType('array');
                break;

            case BoolType::class:
                $definition->addType('boolean');
                break;

            case IntegerType::class:
                $definition->addType('integer');
                break;

            case ArrayOfStringType::class:
                throw new Exception('Not implemented yet.');
        }
    }
}
