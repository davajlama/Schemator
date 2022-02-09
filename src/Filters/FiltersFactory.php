<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Filters;

class FiltersFactory
{
    public function crateTrim(): Trim
    {
        return new Trim();
    }

    public function createUpper(): Upper
    {
        return new Upper();
    }
}
