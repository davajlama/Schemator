<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi;

use Davajlama\Schemator\Schema\Property;
use Davajlama\Schemator\Schema\Schema;

final class SchemaReference
{
    private Schema|Property|string $schema;

    public function __construct(Schema|Property|string $schema)
    {
        $this->schema = $schema;
    }

    public function getSchema(): Schema|Property|string
    {
        return $this->schema;
    }
}
