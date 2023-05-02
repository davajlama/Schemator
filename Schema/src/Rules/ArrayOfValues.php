<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Rules;

use Davajlama\Schemator\Schema\Exception\PropertyIsNotArrayException;

use function in_array;
use function is_array;

final class ArrayOfValues extends BaseRule
{
    /**
     * @var scalar[]
     */
    private array $values;

    /**
     * @param bool[]|float[]|int[]|string[] $values
     */
    public function __construct(array $values, ?string $message = null)
    {
        parent::__construct($message);

        $this->values = $values;
    }

    public function validateValue(mixed $value): void
    {
        if (!is_array($value)) {
            throw new PropertyIsNotArrayException();
        }

        foreach ($value as $item) {
            if (!in_array($item, $this->values, true)) {
                $this->fail('Array contain one or more non-predefined values.');
            }
        }
    }
}
