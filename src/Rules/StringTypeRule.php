<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Rules;

use function is_string;

class StringTypeRule extends BaseRule
{
    public function validateValue($value): void
    {
        if (!is_string($value)) {
            $this->fail('not a String');
        }
    }
}
