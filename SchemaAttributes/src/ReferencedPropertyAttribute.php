<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaAttributes;

use Davajlama\Schemator\Schema\Property;
use Davajlama\Schemator\Schema\Schema;

final class ReferencedPropertyAttribute implements PropertyAttribute
{
    private Schema $schema;

    public function __construct(Schema $schema)
    {
        $this->schema = $schema;
    }

    public function apply(Property $property): void
    {
        $property->ref($this->schema);
    }
}
