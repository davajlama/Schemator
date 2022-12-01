<?php

namespace Davajlama\Schemator\OpenApi;

use Davajlama\Schemator\Schema;

interface SchemaLoaderInterface
{
    public function resolve(string $class): ?Schema;
}
