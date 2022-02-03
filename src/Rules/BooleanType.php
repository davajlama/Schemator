<?php

declare(strict_types=1);


namespace Davajlama\Schemator\Rules;

class BooleanType extends BaseRule
{
    public function validateValue($value)
    {
        if(!is_bool($value)) {
            $this->fail('is not a boolean');
        }
    }
}