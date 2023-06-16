<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Rules\Type;

use Davajlama\Schemator\Schema\Exception\PropertyIsNotArrayException;
use Davajlama\Schemator\Schema\Exception\ValidationFailedException;
use Davajlama\Schemator\Schema\Rules\BaseRule;
use Davajlama\Schemator\Schema\Validator\Message;

use function array_map;
use function array_sum;
use function is_array;
use function is_int;

final class ArrayOfIntegerType extends BaseRule
{
    public function validateValue(mixed $value): void
    {
        if (!is_array($value)) {
            throw new PropertyIsNotArrayException();
        }

        if (array_sum(array_map(static fn($v) => (int) (!is_int($v)), $value)) > 0) {
            throw new ValidationFailedException(new Message('Array contain one or more non-integer values.'));
        }
    }
}
