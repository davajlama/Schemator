<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Extractor;

interface ExtractorAwareInterface
{
    public function setExtractor(Extractor $extractor): void;
}
