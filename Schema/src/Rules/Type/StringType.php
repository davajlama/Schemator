<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Rules\Type;

use Davajlama\Schemator\Exception\PropertyIsNotStringException;
use Davajlama\Schemator\Rules\BaseRule;

use function is_string;

class StringType extends BaseRule
{
    public function validateValue(mixed $value): void
    {
        if (!is_string($value)) {
            throw new PropertyIsNotStringException();
        }
    }
}
