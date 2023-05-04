<?php

declare(strict_types=1);

namespace Davajlama\Schemator\DataSanitizer\Filters;

use Davajlama\Schemator\DataSanitizer\SanitizedValue;

use function is_string;
use function trim;

final class Trim extends BaseFilter
{
    private string $characters;

    public function __construct(string $characters = " \t\n\r\0\x0B")
    {
        $this->characters = $characters;
    }

    protected function filterValue(mixed $value): ?SanitizedValue
    {
        if (is_string($value)) {
            return new SanitizedValue(trim($value, $this->characters));
        }

        return null;
    }
}
