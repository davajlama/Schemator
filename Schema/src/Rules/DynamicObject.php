<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Rules;

use Davajlama\Schemator\Schema\Exception\PropertyIsNotArrayException;

use function array_keys;
use function array_map;
use function array_sum;
use function is_array;
use function is_string;

class DynamicObject extends BaseRule
{
    public function validateValue(mixed $value): void
    {
        if (!is_array($value)) {
            throw new PropertyIsNotArrayException();
        }

        if (array_sum(array_map(static fn($v) => (int) (!is_string($v)), array_keys($value))) > 0) {
            $this->fail('Array contains one or more not-string keys.');
        }
    }
}
