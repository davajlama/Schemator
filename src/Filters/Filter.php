<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Filters;

interface Filter
{
    public function filter(mixed $data, string $property, mixed $value): mixed;
}
