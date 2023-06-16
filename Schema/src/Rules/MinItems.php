<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Rules;

use Davajlama\Schemator\Schema\Exception\PropertyIsNotArrayException;
use Davajlama\Schemator\Schema\Exception\ValidationFailedException;
use Davajlama\Schemator\Schema\Validator\Message;

use function count;
use function is_array;

final class MinItems extends BaseRule
{
    private int $minItems;

    public function __construct(int $minItems)
    {
        $this->minItems = $minItems;
    }

    public function validateValue(mixed $value): void
    {
        if (!is_array($value)) {
            throw new PropertyIsNotArrayException();
        }

        if (count($value) < $this->minItems) {
            throw new ValidationFailedException(new Message('Minimum items of an array is :minItems.', [':minItems' => $this->minItems]));
        }
    }
}
