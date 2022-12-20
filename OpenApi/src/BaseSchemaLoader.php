<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi;

use Davajlama\Schemator\Schema\Schema;

use function class_exists;
use function class_parents;
use function in_array;

final class BaseSchemaLoader implements SchemaLoaderInterface
{
    public function resolve(string $class): ?Schema
    {
        if (!class_exists($class)) {
            return null;
        }

        if (!in_array(Schema::class, class_parents($class), true)) {
            return null;
        }

        return new $class();
    }
}
