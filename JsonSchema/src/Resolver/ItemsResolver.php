<?php

declare(strict_types=1);

namespace Davajlama\JsonSchemaGenerator\Resolver;

use Davajlama\JsonSchemaGenerator\Definition;
use Davajlama\JsonSchemaGenerator\ReflectionExtractor;
use Davajlama\Schemator\RuleInterface;
use Davajlama\Schemator\Rules\MaxItems;
use Davajlama\Schemator\Rules\MinItems;
use Davajlama\Schemator\Rules\Unique;

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
        } else if ($rule instanceof MinItems) {
            /** @var int $minItems */
            $minItems = ReflectionExtractor::getProperty($rule, 'minItems');

            $definition->setMinItems($minItems);
        } else if ($rule instanceof MaxItems) {
            /** @var int $maxItems */
            $maxItems = ReflectionExtractor::getProperty($rule, 'maxItems');

            $definition->setMaxItems($maxItems);
        }
    }
}