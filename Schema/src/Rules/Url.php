<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Rules;

use Davajlama\Schemator\Schema\Exception\PropertyIsNotStringException;
use Davajlama\Schemator\Schema\Exception\ValidationFailedException;
use Davajlama\Schemator\Schema\Validator\Message;

use function filter_var;
use function is_string;

final class Url extends BaseRule
{
    public function validateValue(mixed $value): void
    {
        if (!is_string($value)) {
            throw new PropertyIsNotStringException();
        }

        if (filter_var($value, FILTER_VALIDATE_URL) === false) {
            throw new ValidationFailedException(new Message('Invalid url format.'));
        }
    }
}
