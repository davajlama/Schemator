<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Rules;

use Davajlama\Schemator\Extractor\ValueExtractor;

interface ExtractorAwareInterface
{
    public function setExtractor(ValueExtractor $extractor): void;
}