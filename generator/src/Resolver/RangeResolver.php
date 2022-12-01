<?php

declare(strict_types=1);

namespace Davajlama\JsonSchemaGenerator\Resolver;

use Davajlama\JsonSchemaGenerator\Definition;
use Davajlama\JsonSchemaGenerator\ReflectionExtractor;
use Davajlama\Schemator\RuleInterface;
use Davajlama\Schemator\Rules\Range;

final class RangeResolver implements ResolverInterface
{
    public function support(RuleInterface $rule): bool
    {
        return $rule instanceof Range;
    }

    public function resolve(Definition $definition, RuleInterface $rule): void
    {
        /** @var float $min */
        $min = ReflectionExtractor::getProperty($rule, 'min');

        /** @var float $max */
        $max = ReflectionExtractor::getProperty($rule, 'max');

        $definition->setMinimum($min);
        $definition->setMaximum($max);
    }
}
