<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema;

interface SchemaFactoryInterface
{
    public function create(Schema|string $schema): Schema;
}
