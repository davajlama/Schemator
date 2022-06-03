<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Rules;

use function is_int;

class IntegerType extends BaseRule
{
    public function validateValue(mixed $list): void
    {
        if (!is_int($list)) {
            $this->fail('Must be an integer.');
        }
    }
}
