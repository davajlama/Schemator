<?php

declare(strict_types=1);


namespace Davajlama\Schemator\Rules;

class IntegerType extends BaseRule
{
    public function validateValue($value)
    {
        if (is_int($value) === false) {
            $this->fail('Must be an integer type.');
        }
    }
}