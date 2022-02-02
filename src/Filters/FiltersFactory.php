<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Filters;

class FiltersFactory
{
    public function crateTrim()
    {
        return new Trim();
    }
}