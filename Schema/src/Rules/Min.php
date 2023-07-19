<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Rules;

use Davajlama\Schemator\Schema\Exception\ValidationFailedException;
use Davajlama\Schemator\Schema\Validator\Message;

use function is_float;
use function is_integer;

class Min extends BaseRule
{
    private float $min;

    public function __construct(float $min)
    {
        $this->min = $min;
    }

    public function validateValue(mixed $value): void
    {
        if (!is_float($value) && !is_integer($value)) {
            throw new ValidationFailedException(new Message('Must be a float or an integer.'));
        }

        if ($value < $this->min) {
            throw new ValidationFailedException(new Message('Must be greater than :min.', [':min' => $this->min]));
        }
    }
}
