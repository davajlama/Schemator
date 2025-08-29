<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaAttributes\Attribute;

use Attribute;
use Davajlama\Schemator\Schema\Property;
use Davajlama\Schemator\SchemaAttributes\PropertyAttribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
final class Description implements PropertyAttribute
{
    private string $description;

    public function __construct(string $description)
    {
        $this->description = $description;
    }

    public function apply(Property $property): void
    {
        $property->description($this->description);
    }
}
