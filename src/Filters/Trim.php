<?php

declare(strict_types=1);


namespace Davajlama\Schemator\Filters;

class Trim extends BaseFilter
{
    public function filterValue($value)
    {
        if (is_string($value)) {
            return trim($value);
        }

        return $value;
    }
}