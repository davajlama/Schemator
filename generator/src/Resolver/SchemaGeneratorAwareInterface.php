<?php

namespace Davajlama\JsonSchemaGenerator\Resolver;

use Davajlama\JsonSchemaGenerator\SchemaGenerator;

interface SchemaGeneratorAwareInterface
{
    public function setSchemaGenerator(SchemaGenerator $schemaGenerator): void;
}
