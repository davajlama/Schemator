<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Rules\Type;

use Davajlama\Schemator\Schema\Exception\PropertyIsNotArrayException;
use Davajlama\Schemator\Schema\Rules\BaseRule;

use function array_map;
use function array_sum;
use function is_array;
use function is_string;

final class ArrayOfStringType extends BaseRule
{
    public function validateValue(mixed $value): void
    {
        if (!is_array($value)) {
            throw new PropertyIsNotArrayException();
        }

        if (array_sum(array_map(static fn($v) => (int) (!is_string($v)), $value)) > 0) {
            $this->fail('Array contain one or more non-string values.');
        }
    }
}
