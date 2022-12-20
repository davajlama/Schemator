<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Rules\Type;

use Davajlama\Schemator\Exception\ValidationFailedException;
use Davajlama\Schemator\Rules\BaseRule;

use function is_int;

class IntegerType extends BaseRule
{
    public function validateValue(mixed $value): void
    {
        if (!is_int($value)) {
            throw new ValidationFailedException($this->getMessage('Must be an integer.'));
        }
    }
}
