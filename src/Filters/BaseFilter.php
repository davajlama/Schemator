<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Filters;

use Davajlama\Schemator\Extractor\Extractor;
use Davajlama\Schemator\Extractor\ExtractorAwareInterface;
use RuntimeException;

abstract class BaseFilter implements Filter, ExtractorAwareInterface
{
    private ?Extractor $extractor = null;

    public function filter(mixed $data, string $property, mixed $value): mixed
    {
        return $this->filterValue($value);
    }

    abstract public function filterValue(mixed $value): mixed;

    public function getExtractor(): Extractor
    {
        if ($this->extractor === null) {
            throw new RuntimeException('None extractor');
        }

        return $this->extractor;
    }

    public function setExtractor(Extractor $extractor): void
    {
        $this->extractor = $extractor;
    }
}
