<?php

declare(strict_types=1);

namespace Davajlama\JsonSchemaGenerator\Resolver;

use Davajlama\JsonSchemaGenerator\Definition;
use Davajlama\Schemator\RuleInterface;
use Davajlama\Schemator\Rules\Email;

final class FormatResolver implements ResolverInterface
{
    public function support(RuleInterface $rule): bool
    {
        return $rule instanceof Email;
    }

    public function resolve(Definition $definition, RuleInterface $rule): void
    {
        $definition->setFormat('email');
    }
}