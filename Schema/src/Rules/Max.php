<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Rules;

use Davajlama\Schemator\Schema\Exception\ValidationFailedException;

use function is_float;
use function is_integer;
use function sprintf;

class Max extends BaseRule
{
    private float $max;

    public function __construct(float $max, ?string $message = null)
    {
        parent::__construct($message);

        $this->max = $max;
    }

    public function validateValue(mixed $value): void
    {
        if (!is_float($value) && !is_integer($value)) {
            throw new ValidationFailedException('Must be a Float or Integer');
        }

        if ($value > $this->max) {
            throw new ValidationFailedException(sprintf($this->getMessage('Must be lower than %d'), $this->max));
        }
    }
}
