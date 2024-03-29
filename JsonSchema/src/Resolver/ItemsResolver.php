<?php

declare(strict_types=1);

namespace Davajlama\Schemator\JsonSchema\Resolver;

use Davajlama\Schemator\JsonSchema\Definition;
use Davajlama\Schemator\JsonSchema\ReflectionExtractor;
use Davajlama\Schemator\Schema\RuleInterface;
use Davajlama\Schemator\Schema\Rules\MaxItems;
use Davajlama\Schemator\Schema\Rules\MinItems;
use Davajlama\Schemator\Schema\Rules\Unique;

final class ItemsResolver implements ResolverInterface
{
    public function support(RuleInterface $rule): bool
    {
        return $rule instanceof Unique
            || $rule instanceof MinItems
            || $rule instanceof MaxItems;
    }

    public function resolve(Definition $definition, RuleInterface $rule): void
    {
        if ($rule instanceof Unique) {
            $definition->setUniqueItems(true);
        } elseif ($rule instanceof MinItems) {
            /** @var int $minItems */
            $minItems = ReflectionExtractor::getProperty($rule, 'minItems');

            $definition->setMinItems($minItems);
        } elseif ($rule instanceof MaxItems) {
            /** @var int $maxItems */
            $maxItems = ReflectionExtractor::getProperty($rule, 'maxItems');

            $definition->setMaxItems($maxItems);
        }
    }
}
