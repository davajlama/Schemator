<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Rules\Type;

use Davajlama\Schemator\Exception\ValidationFailedException;
use Davajlama\Schemator\Rules\BaseRule;

use function is_float;
use function is_integer;

class FloatType extends BaseRule
{
    public function validateValue(mixed $value): void
    {
        if (!is_float($value) && !is_integer($value)) {
            throw new ValidationFailedException($this->getMessage('Must be a float.'));
        }
    }
}
