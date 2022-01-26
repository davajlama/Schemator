<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Extractor;

interface ValueExtractor
{
    public function extract($data, string $property);
}