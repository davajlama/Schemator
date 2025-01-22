<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Rules\Type;

use Davajlama\Schemator\Schema\Exception\ValidationFailedException;
use Davajlama\Schemator\Schema\Rules\BaseRule;
use Davajlama\Schemator\Schema\Validator\Message;

final class NumberType extends BaseRule
{
    public function validateValue(mixed $value): void
    {
        if (!is_int($value) && !is_float($value)) {
            throw new ValidationFailedException(new Message('Must be an integer or a float.'));
        }
    }
}
