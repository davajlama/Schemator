<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Rules\Type;

use Davajlama\Schemator\Schema\Exception\ValidationFailedException;
use Davajlama\Schemator\Schema\Rules\BaseRule;
use Davajlama\Schemator\Schema\Validator\Message;

use function is_bool;

class BoolType extends BaseRule
{
    public function validateValue(mixed $value): void
    {
        if (!is_bool($value)) {
            throw new ValidationFailedException(new Message('Must be a boolean.'));
        }
    }
}
