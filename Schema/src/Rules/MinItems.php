<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Rules;

use Davajlama\Schemator\Exception\PropertyIsNotArrayException;

use function count;
use function is_array;
use function sprintf;

final class MinItems extends BaseRule
{
    private int $minItems;

    public function __construct(int $minItems, ?string $message = null)
    {
        parent::__construct($message);

        $this->minItems = $minItems;
    }

    public function validateValue(mixed $value): void
    {
        if (!is_array($value)) {
            throw new PropertyIsNotArrayException();
        }

        if (count($value) < $this->minItems) {
            $this->fail(sprintf('Minimum items of an array is %d, %d given.', $this->minItems, count($value)));
        }
    }
}
