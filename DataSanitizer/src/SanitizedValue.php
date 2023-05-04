<?php

declare(strict_types=1);

namespace Davajlama\Schemator\DataSanitizer;

final class SanitizedValue
{
    private mixed $value;

    public function __construct(mixed $value)
    {
        $this->value = $value;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }
}
