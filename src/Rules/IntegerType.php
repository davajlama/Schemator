<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Rules;

use function is_int;

class IntegerType extends BaseRule
{
    public function validateValue(mixed $value): void
    {
        if (is_int($value) === false) {
            $this->fail('Must be an integer type.');
        }
    }
}
