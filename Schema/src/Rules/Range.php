<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Rules;

use Davajlama\Schemator\Exception\ValidationFailedException;

use function is_float;
use function is_integer;
use function sprintf;

class Range extends BaseRule
{
    private float $min;

    private float $max;

    public function __construct(float $min, float $max, ?string $message = null)
    {
        parent::__construct($message);

        $this->min = $min;
        $this->max = $max;
    }

    public function validateValue(mixed $value): void
    {
        if (!is_float($value) && !is_integer($value)) {
            throw new ValidationFailedException('Must be a Float or Integer');
        }

        if ($value < $this->min || $value > $this->max) {
            throw new ValidationFailedException(sprintf($this->getMessage('Must be between %d - %d.'), $this->min, $this->max));
        }
    }
}
