<?php

declare(strict_types=1);

namespace Davajlama\Schemator\DataSanitizer\Filters;

use Davajlama\Schemator\DataSanitizer\SanitizedValue;

use function is_numeric;

final class NumericToFloat extends BaseFilter
{
    protected function filterValue(mixed $value): ?SanitizedValue
    {
        if (is_numeric($value)) {
            return new SanitizedValue((float) $value);
        }

        return null;
    }
}
