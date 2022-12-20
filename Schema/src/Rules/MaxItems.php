<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Rules;

use Davajlama\Schemator\Exception\PropertyIsNotArrayException;

use function count;
use function is_array;
use function sprintf;

final class MaxItems extends BaseRule
{
    private int $maxItems;

    public function __construct(int $maxItems, ?string $message = null)
    {
        parent::__construct($message);

        $this->maxItems = $maxItems;
    }

    public function validateValue(mixed $value): void
    {
        if (!is_array($value)) {
            throw new PropertyIsNotArrayException();
        }

        if (count($value) > $this->maxItems) {
            $this->fail(sprintf('Maximum items of an array is %d, %d given.', $this->maxItems, count($value)));
        }
    }
}
