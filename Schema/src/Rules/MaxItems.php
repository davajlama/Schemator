<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Rules;

use Davajlama\Schemator\Schema\Exception\PropertyIsNotArrayException;
use Davajlama\Schemator\Schema\Exception\ValidationFailedException;
use Davajlama\Schemator\Schema\Validator\Message;

use function count;
use function is_array;

final class MaxItems extends BaseRule
{
    private int $maxItems;

    public function __construct(int $maxItems)
    {
        $this->maxItems = $maxItems;
    }

    public function validateValue(mixed $value): void
    {
        if (!is_array($value)) {
            throw new PropertyIsNotArrayException();
        }

        if (count($value) > $this->maxItems) {
            throw new ValidationFailedException(new Message('Maximum items of an array is :maxItems.', [':maxItems' => $this->maxItems]));
        }
    }
}
