<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Rules;

use Davajlama\Schemator\Schema\Exception\ValidationFailedException;
use Davajlama\Schemator\Schema\Validator\Message;

use function is_float;
use function is_integer;

class Range extends BaseRule
{
    private float $min;

    private float $max;

    public function __construct(float $min, float $max)
    {
        $this->min = $min;
        $this->max = $max;
    }

    public function validateValue(mixed $value): void
    {
        if (!is_float($value) && !is_integer($value)) {
            throw new ValidationFailedException(new Message('Must be a float or an integer.'));
        }

        if ($value < $this->min || $value > $this->max) {
            throw new ValidationFailedException(new Message('Must be between :min - :max.', [':min' => $this->min, ':max' => $this->max]));
        }
    }
}
