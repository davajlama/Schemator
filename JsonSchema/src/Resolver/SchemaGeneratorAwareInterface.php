<?php

declare(strict_types=1);

namespace Davajlama\Schemator\JsonSchema\Resolver;

use Davajlama\Schemator\JsonSchema\SchemaGenerator;

interface SchemaGeneratorAwareInterface
{
    public function setSchemaGenerator(SchemaGenerator $schemaGenerator): void;
}
