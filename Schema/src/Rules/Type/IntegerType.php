<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Rules\Type;

use Davajlama\Schemator\Schema\Exception\ValidationFailedException;
use Davajlama\Schemator\Schema\Rules\BaseRule;
use Davajlama\Schemator\Schema\Validator\Message;

use function is_int;

class IntegerType extends BaseRule
{
    public function validateValue(mixed $value): void
    {
        if (!is_int($value)) {
            throw new ValidationFailedException(new Message('Must be an integer.'));
        }
    }
}
