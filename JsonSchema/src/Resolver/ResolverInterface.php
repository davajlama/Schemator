<?php

declare(strict_types=1);

namespace Davajlama\JsonSchemaGenerator\Resolver;

use Davajlama\JsonSchemaGenerator\Definition;
use Davajlama\Schemator\RuleInterface;

interface ResolverInterface
{
    public function support(RuleInterface $rule): bool;

    public function resolve(Definition $definition, RuleInterface $rule): void;
}
