<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Filters;

class DefaultValue extends BaseFilter
{
    private mixed $defaultValue;

    public function __construct(mixed $defaultValue)
    {
        $this->defaultValue = $defaultValue;
    }

    public function filterValue($value): void
    {
    }
}
