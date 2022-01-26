<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Rules;

class StringTypeRule extends BaseRule
{
    public function validateValue($value)
    {
        if(!is_string($value)) {
            $this->fail('not a String');
        }
    }

}