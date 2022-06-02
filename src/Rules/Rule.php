<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Rules;

interface Rule
{
    public function validate(mixed $data, string $property): bool;
}
