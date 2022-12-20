<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Rules;

use Davajlama\Schemator\Exception\ValidationFailedException;

use function is_float;
use function is_integer;
use function sprintf;

class Min extends BaseRule
{
    private float $min;

    public function __construct(float $min, ?string $message = null)
    {
        parent::__construct($message);

        $this->min = $min;
    }

    public function validateValue(mixed $value): void
    {
        if (!is_float($value) && !is_integer($value)) {
            throw new ValidationFailedException('Must be a Float or Integer');
        }

        if ($value < $this->min) {
            throw new ValidationFailedException(sprintf($this->getMessage('Must be greater than %d'), $this->min));
        }
    }
}
