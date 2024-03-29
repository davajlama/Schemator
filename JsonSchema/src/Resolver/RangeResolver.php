<?php

declare(strict_types=1);

namespace Davajlama\Schemator\JsonSchema\Resolver;

use Davajlama\Schemator\JsonSchema\Definition;
use Davajlama\Schemator\JsonSchema\ReflectionExtractor;
use Davajlama\Schemator\Schema\RuleInterface;
use Davajlama\Schemator\Schema\Rules\Max;
use Davajlama\Schemator\Schema\Rules\Min;
use Davajlama\Schemator\Schema\Rules\Range;

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
        } elseif ($rule instanceof Min) {
            /** @var float $min */
            $min = ReflectionExtractor::getProperty($rule, 'min');

            $definition->setMinimum($min);
        } elseif ($rule instanceof Max) {
            /** @var float $max */
            $max = ReflectionExtractor::getProperty($rule, 'max');

            $definition->setMaximum($max);
        }
    }
}
