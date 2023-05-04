<?php

declare(strict_types=1);

namespace Davajlama\Schemator\DataSanitizer\Filters;

use Davajlama\Schemator\DataSanitizer\SanitizedValue;

use function is_string;
use function strlen;

final class EmptyStringToNull extends BaseFilter
{
    protected function filterValue(mixed $value): ?SanitizedValue
    {
        if (is_string($value) && strlen($value) === 0) {
            return new SanitizedValue(null);
        }

        return null;
    }
}
