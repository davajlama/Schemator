<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Extractor;

interface ExtractorInterface
{
    public function extract(mixed $data, string $property): mixed;
}
