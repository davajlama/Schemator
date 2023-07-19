<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Rules;

use Davajlama\Schemator\Schema\Exception\PropertyIsNotStringException;
use Davajlama\Schemator\Schema\Exception\ValidationFailedException;
use Davajlama\Schemator\Schema\Validator\Message;

use function is_string;
use function strlen;

class MaxLength extends BaseRule
{
    private int $maxLength;

    public function __construct(int $maxLength)
    {
        $this->maxLength = $maxLength;
    }

    public function validateValue(mixed $value): void
    {
        if (!is_string($value)) {
            throw new PropertyIsNotStringException();
        }

        if ($this->maxLength < strlen($value)) {
            throw new ValidationFailedException(new Message('Must be max :maxLength chars length.', [':maxLength' => $this->maxLength]));
        }
    }
}
