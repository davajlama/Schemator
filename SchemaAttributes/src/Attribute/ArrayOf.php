<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaAttributes\Attribute;

use Attribute;
use Davajlama\Schemator\Schema\Property;
use Davajlama\Schemator\Schema\Schema;
use Davajlama\Schemator\SchemaAttributes\PropertyAttribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class ArrayOf implements PropertyAttribute
{
    private Schema|string $schema;

    public function __construct(Schema|string $schema)
    {
        $this->schema = $schema;
    }

    public function apply(Property $property): void
    {
        $property->arrayOf($this->schema);
    }
}
