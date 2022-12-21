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

        $parents = class_parents($class);
        if ($parents === false || !in_array(Schema::class, $parents, true)) {
            return null;
        }

        /** @var Schema $object */
        $object = new $class();

        return $object;
    }
}
