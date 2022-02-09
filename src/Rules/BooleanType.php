<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Rules;

use function is_bool;

class BooleanType extends BaseRule
{
    public function validateValue($value): void
    {
        if (!is_bool($value)) {
            $this->fail('is not a boolean');
        }
    }
}
