<?php

declare(strict_types=1);

namespace Davajlama\Schemator\DataSanitizer\Filters;

use Davajlama\Schemator\DataSanitizer\SanitizedValue;

use function is_string;

final class StringedNumberToFloat extends BaseFilter
{
    protected function filterValue(mixed $value): ?SanitizedValue
    {
        if (is_string($value)) {
            $tempValue = (string) (float) $value;
            if ($tempValue === $value) {
                return new SanitizedValue((float) $value);
            }
        }

        return null;
    }
}
