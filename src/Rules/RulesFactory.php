<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Rules;

class RulesFactory
{
    public function create(string $class, ...$arguments): Rule
    {
        return new $class(...$arguments);
    }
}
