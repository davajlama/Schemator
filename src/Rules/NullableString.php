<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Rules;

class NullableString extends StringTypeRule
{
    public function validateValue(mixed $value): void
    {
        if ($value !== null) {
            parent::validateValue($value);
        }
    }
}
