<?php

declare(strict_types=1);

namespace Davajlama\Schemator\DataSanitizer\Filters;

use Davajlama\Schemator\DataSanitizer\SanitizedValue;

use function is_string;
use function preg_replace;

final class Spaceless extends BaseFilter
{
    protected function filterValue(mixed $value): ?SanitizedValue
    {
        if (is_string($value)) {
            return new SanitizedValue(preg_replace('~\s+~', '', $value));
        }

        return null;
    }
}
