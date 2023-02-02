<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema;

use LogicException;

use function array_key_exists;
use function class_exists;
use function class_parents;
use function in_array;
use function sprintf;

trait SchemaFactoryHelper
{
    /**
     * @var array<string, Schema>
     */
    private array $schemaCollection = [];

    private function createSchema(Schema|string $schema): Schema
    {
        if ($schema instanceof Schema) {
            return $schema;
        }

        if (array_key_exists($schema, $this->schemaCollection)) {
            return $this->schemaCollection[$schema];
        }

        if (!class_exists($schema)) {
            throw new LogicException(sprintf('Schema %s not exists.', $schema));
        }

        $parents = class_parents($schema);
        if ($parents === false || in_array(Schema::class, $parents, true) === false) {
            throw new LogicException(sprintf('Schema must be instance of %s, %s given.', Schema::class, $schema));
        }

        /** @var Schema $object */
        $object = new $schema();

        return $this->schemaCollection[$schema] = $object;
    }
}
