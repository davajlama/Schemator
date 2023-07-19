<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Rules;

use Davajlama\Schemator\Schema\Exception\PropertyIsNotStringException;
use Davajlama\Schemator\Schema\Exception\ValidationFailedException;
use Davajlama\Schemator\Schema\Validator\Message;

use function is_string;
use function strlen;

class Length extends BaseRule
{
    private int $length;

    public function __construct(int $length)
    {
        $this->length = $length;
    }

    public function validateValue(mixed $value): void
    {
        if (!is_string($value)) {
            throw new PropertyIsNotStringException();
        }

        if ($this->length !== strlen($value)) {
            throw new ValidationFailedException(new Message('Must be :length chars length.', [':length' => $this->length]));
        }
    }
}
