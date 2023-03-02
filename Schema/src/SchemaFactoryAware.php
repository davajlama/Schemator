<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema;

use LogicException;

trait SchemaFactoryAware
{
    private ?SchemaFactoryInterface $schemaFactory = null;

    public function getSchemaFactory(): SchemaFactoryInterface
    {
        if ($this->schemaFactory === null) {
            throw new LogicException('No schema factory.');
        }

        return $this->schemaFactory;
    }

    public function setSchemaFactory(SchemaFactoryInterface $schemaFactory): void
    {
        $this->schemaFactory = $schemaFactory;
    }
}
