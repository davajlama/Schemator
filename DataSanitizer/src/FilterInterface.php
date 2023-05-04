<?php

declare(strict_types=1);

namespace Davajlama\Schemator\DataSanitizer;

interface FilterInterface
{
    public function filter(mixed $payload, string $property): ?SanitizedValue;
}
