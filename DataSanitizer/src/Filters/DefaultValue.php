<?php

declare(strict_types=1);

namespace Davajlama\Schemator\DataSanitizer\Filters;

use Davajlama\Schemator\DataSanitizer\FilterInterface;
use Davajlama\Schemator\DataSanitizer\SanitizedValue;
use Davajlama\Schemator\Schema\Extractor\ExtractorAware;
use Davajlama\Schemator\Schema\Extractor\ExtractorAwareInterface;

final class DefaultValue implements FilterInterface, ExtractorAwareInterface
{
    use ExtractorAware;

    private mixed $default;

    public function __construct(mixed $default)
    {
        $this->default = $default;
    }

    public function filter(mixed $payload, string $property): ?SanitizedValue
    {
        if (!$this->getExtractor()->exists($payload, $property) || $this->getExtractor()->extract($payload, $property) === null) {
            return new SanitizedValue($this->default);
        }

        return null;
    }
}
