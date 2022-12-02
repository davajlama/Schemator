<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi;

use Davajlama\Schemator\Schema;

interface SchemaLoaderInterface
{
    public function resolve(string $class): ?Schema;
}
