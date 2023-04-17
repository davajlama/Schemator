<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi;

use Davajlama\Schemator\Schema\Schema;

final class SchemaReference
{
    private Schema|string $schema;

    public function __construct(Schema|string $schema)
    {
        $this->schema = $schema;
    }

    public function getSchema(): Schema|string
    {
        return $this->schema;
    }
}
