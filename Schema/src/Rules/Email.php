<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Rules;

use Davajlama\Schemator\Exception\ValidationFailedException;

use function filter_var;
use function is_string;

class Email extends BaseRule
{
    public function validateValue(mixed $value): void
    {
        if (!is_string($value)) {
            throw new ValidationFailedException('Must be a string.');
        }

        if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            throw new ValidationFailedException($this->getMessage('Wrong e-mail format.'));
        }
    }
}
