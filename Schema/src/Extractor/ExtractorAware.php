<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Extractor;

use LogicException;

trait ExtractorAware
{
    private ?ExtractorInterface $extractor = null;

    public function getExtractor(): ExtractorInterface
    {
        if ($this->extractor === null) {
            throw new LogicException('None extractor.');
        }

        return $this->extractor;
    }

    public function setExtractor(ExtractorInterface $extractor): void
    {
        $this->extractor = $extractor;
    }
}
