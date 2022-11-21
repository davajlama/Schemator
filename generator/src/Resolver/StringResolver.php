<?php

declare(strict_types=1);

namespace Davajlama\JsonSchemaGenerator\Resolver;

use Davajlama\JsonSchemaGenerator\Definition;
use Davajlama\Schemator\RuleInterface;
use Davajlama\Schemator\Rules\Type\StringType;

final class StringResolver implements ResolverInterface
{
    public function support(RuleInterface $rule): bool
    {
        return $rule instanceof StringType;
    }

    public function resolve(Definition $definition, RuleInterface $rule): void
    {
        $definition->addType('string');
    }
}
