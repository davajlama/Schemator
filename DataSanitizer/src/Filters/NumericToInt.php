<?php

declare(strict_types=1);

namespace Davajlama\Schemator\DataSanitizer\Filters;

use Davajlama\Schemator\DataSanitizer\SanitizedValue;

use function is_numeric;

final class NumericToInt extends BaseFilter
{
    protected function filterValue(mixed $value): ?SanitizedValue
    {
        if (is_numeric($value)) {
            return new SanitizedValue((int) $value);
        }

        return null;
    }
}
