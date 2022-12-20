<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Extractor;

interface ExtractorAwareInterface
{
    public function setExtractor(ExtractorInterface $extractor): void;
}
