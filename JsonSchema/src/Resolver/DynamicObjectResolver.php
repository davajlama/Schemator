<?php

declare(strict_types=1);

namespace Davajlama\Schemator\JsonSchema\Resolver;

use Davajlama\Schemator\JsonSchema\Definition;
use Davajlama\Schemator\Schema\RuleInterface;
use Davajlama\Schemator\Schema\Rules\DynamicObject;

final class DynamicObjectResolver implements ResolverInterface
{
    public function support(RuleInterface $rule): bool
    {
        return $rule instanceof DynamicObject;
    }

    public function resolve(Definition $definition, RuleInterface $rule): void
    {
        $definition->addType('object');
    }
}
