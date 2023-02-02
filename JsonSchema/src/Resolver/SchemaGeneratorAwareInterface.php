<?php

declare(strict_types=1);

namespace Davajlama\Schemator\JsonSchema\Resolver;

use Davajlama\Schemator\JsonSchema\JsonSchemaBuilder;

interface SchemaGeneratorAwareInterface
{
    public function setSchemaGenerator(JsonSchemaBuilder $schemaGenerator): void;
}
