<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Rules;

class RulesFactory
{
    public function create(string $class, mixed ...$arguments): Rule
    {
        /** @var Rule $rule */
        $rule = new $class(...$arguments);

        return $rule;
    }
}
