<?php

declare(strict_types=1);

namespace Davajlama\Schemator\JsonSchema\Resolver;

use Davajlama\Schemator\JsonSchema\Definition;
use Davajlama\Schemator\Schema\RuleInterface;

interface ResolverInterface
{
    public function support(RuleInterface $rule): bool;

    public function resolve(Definition $definition, RuleInterface $rule): void;
}
