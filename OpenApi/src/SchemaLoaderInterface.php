<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\OpenApi;

use Davajlama\Schemator\Schema\Schema;

interface SchemaLoaderInterface
{
    public function resolve(string $class): ?Schema;
}
