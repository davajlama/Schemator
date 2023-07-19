<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Rules\Type;

use Davajlama\Schemator\Schema\Exception\ValidationFailedException;
use Davajlama\Schemator\Schema\Rules\BaseRule;
use Davajlama\Schemator\Schema\Validator\Message;

use function is_float;
use function is_integer;

class FloatType extends BaseRule
{
    public function validateValue(mixed $value): void
    {
        if (!is_float($value) && !is_integer($value)) {
            throw new ValidationFailedException(new Message('Must be a float.'));
        }
    }
}
