<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema;

interface SchemaFactoryAwareInterface
{
    public function setSchemaFactory(SchemaFactoryInterface $schemaFactory): void;
}
