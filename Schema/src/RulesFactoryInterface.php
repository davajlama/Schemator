<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema;

interface RulesFactoryInterface
{
    /**
     * @param mixed[] $arguments
     */
    public function create(string $name, array $arguments): ?RuleInterface;
}
