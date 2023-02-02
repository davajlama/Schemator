<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Rules\Type;

use Davajlama\Schemator\Schema\Exception\PropertyIsNotStringException;
use Davajlama\Schemator\Schema\Rules\BaseRule;

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
