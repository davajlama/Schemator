<?php

declare(strict_types=1);

namespace Davajlama\Schemator\DataSanitizer\Filters;

use Davajlama\Schemator\DataSanitizer\SanitizedValue;

use function is_string;

final class StringedNumberToInt extends BaseFilter
{
    protected function filterValue(mixed $value): ?SanitizedValue
    {
        if (is_string($value)) {
            $tempValue = (string) (int) $value;
            if ($tempValue === $value) {
                return new SanitizedValue((int) $value);
            }
        }

        return null;
    }
}
