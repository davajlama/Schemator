<?php

declare(strict_types=1);

namespace Davajlama\Schemator;

use LogicException;

use function array_key_exists;
use function class_exists;
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

        $object = new $schema();
        if ($object instanceof Schema === false) {
            throw new LogicException(sprintf('Schema %s must be instance of %s, %s given.', $schema, Schema::class, $object::class));
        }

        return $this->schemaCollection[$schema] = $object;
    }
}
