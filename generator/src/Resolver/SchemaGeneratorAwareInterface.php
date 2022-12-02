<?php

declare(strict_types=1);

namespace Davajlama\JsonSchemaGenerator\Resolver;

use Davajlama\JsonSchemaGenerator\SchemaGenerator;

interface SchemaGeneratorAwareInterface
{
    public function setSchemaGenerator(SchemaGenerator $schemaGenerator): void;
}
