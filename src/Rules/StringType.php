<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Rules;

use function is_string;

class StringType extends BaseRule
{
    public function validateValue(mixed $list): void
    {
        if (!is_string($list)) {
            $this->fail('Must be a string.');
        }
    }
}
