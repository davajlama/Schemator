<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema;

use Davajlama\Schemator\SchemaAttributes\SchemaBuilder;
use LogicException;

use function array_key_exists;
use function class_exists;
use function class_parents;
use function in_array;
use function sprintf;

final class SchemaFactory implements SchemaFactoryInterface
{
    /**
     * @var array<string, Schema>
     */
    private array $schemaCollection = [];

    public function create(Schema|string $schema): Schema
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

        $object = null;

        $parents = class_parents($schema);
        if ($parents !== false && in_array(Schema::class, $parents, true) !== false) {
            /** @var Schema $object */
            $object = new $schema();
        }

        if ($object === null) {
            $object = (new SchemaBuilder())->build($schema);
        }

        return $this->schemaCollection[$schema] = $object;
    }
}
