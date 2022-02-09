<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Filters;

use function is_string;
use function mb_strtoupper;

class Upper extends BaseFilter
{
    public function filterValue(mixed $value): mixed
    {
        if (is_string($value)) {
            return mb_strtoupper($value);
        }

        return $value;
    }
}
