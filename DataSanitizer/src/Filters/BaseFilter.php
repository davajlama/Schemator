<?php

declare(strict_types=1);

namespace Davajlama\Schemator\DataSanitizer\Filters;

use Davajlama\Schemator\DataSanitizer\FilterInterface;
use Davajlama\Schemator\DataSanitizer\SanitizedValue;
use Davajlama\Schemator\Schema\Extractor\ExtractorAware;
use Davajlama\Schemator\Schema\Extractor\ExtractorAwareInterface;

abstract class BaseFilter implements FilterInterface, ExtractorAwareInterface
{
    use ExtractorAware;

    public function filter(mixed $payload, string $property): ?SanitizedValue
    {
        if ($this->getExtractor()->exists($payload, $property)) {
            return $this->filterValue($this->getExtractor()->extract($payload, $property));
        }

        return null;
    }

    abstract protected function filterValue(mixed $value): ?SanitizedValue;
}
