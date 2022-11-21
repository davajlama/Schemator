<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Rules\Type;

use Davajlama\Schemator\Exception\PropertyIsNotArrayException;
use Davajlama\Schemator\Rules\BaseRule;

use function is_array;

class ArrayType extends BaseRule
{
    public function validateValue(mixed $value): void
    {
        if (!is_array($value)) {
            throw new PropertyIsNotArrayException();
        }
    }
}
