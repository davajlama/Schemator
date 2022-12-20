<?php

declare(strict_types=1);

namespace Davajlama\JsonSchemaGenerator\Resolver;

use Davajlama\JsonSchemaGenerator\Definition;
use Davajlama\JsonSchemaGenerator\ReflectionExtractor;
use Davajlama\Schemator\RuleInterface;
use Davajlama\Schemator\Rules\Max;
use Davajlama\Schemator\Rules\Min;
use Davajlama\Schemator\Rules\Range;

final class RangeResolver implements ResolverInterface
{
    public function support(RuleInterface $rule): bool
    {
        return $rule instanceof Range
            || $rule instanceof Min
            || $rule instanceof Max;
    }

    public function resolve(Definition $definition, RuleInterface $rule): void
    {
        if ($rule instanceof Range) {
            /** @var float $min */
            $min = ReflectionExtractor::getProperty($rule, 'min');

            /** @var float $max */
            $max = ReflectionExtractor::getProperty($rule, 'max');

            $definition->setMinimum($min);
            $definition->setMaximum($max);
        } else if ($rule instanceof Min) {
            /** @var float $min */
            $min = ReflectionExtractor::getProperty($rule, 'min');

            $definition->setMinimum($min);
        } else if ($rule instanceof Max) {
            /** @var float $max */
            $max = ReflectionExtractor::getProperty($rule, 'max');

            $definition->setMaximum($max);
        }
    }
}
