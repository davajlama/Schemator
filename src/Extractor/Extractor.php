<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Extractor;

interface Extractor
{
    public function extract($data, string $property): void;
}
