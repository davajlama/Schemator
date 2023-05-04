<?php

declare(strict_types=1);

namespace Davajlama\Schemator\DataSanitizer\Filters;

use Davajlama\Schemator\DataSanitizer\SanitizedValue;

use function is_string;
use function str_replace;

final class Replace extends BaseFilter
{
    private string $search;

    private string $replace;

    public function __construct(string $search, string $replace)
    {
        $this->search = $search;
        $this->replace = $replace;
    }

    protected function filterValue(mixed $value): ?SanitizedValue
    {
        if (is_string($value)) {
            return new SanitizedValue(str_replace($this->search, $this->replace, $value));
        }

        return null;
    }
}
