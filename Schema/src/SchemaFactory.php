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

class SchemaFactory implements SchemaFactoryInterface
{
    /**
     * @var SchemaBuilder<object>
     */
    private SchemaBuilder $schemaBuilder;

    /**
     * @var array<string, Schema>
     */
    private array $schemaCollection = [];

    /**
     * @param SchemaBuilder<object>|null $schemaBuilder
     */
    public function __construct(?SchemaBuilder $schemaBuilder = null)
    {
        $this->schemaBuilder = $schemaBuilder ?? new SchemaBuilder();
    }

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

        if ($object === null && class_exists(SchemaBuilder::class)) {
            $object = $this->schemaBuilder->build($schema);
        }

        if ($object === null) {
            throw new LogicException(sprintf('Schema %s not exists.', $schema));
        }

        return $this->schemaCollection[$schema] = $object;
    }
}
