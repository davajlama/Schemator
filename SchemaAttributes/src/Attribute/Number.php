<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaAttributes\Attribute;

use Attribute;
use Davajlama\Schemator\Schema\Property;
use Davajlama\Schemator\SchemaAttributes\PropertyAttribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
final class Number implements PropertyAttribute
{
    public function apply(Property $property): void
    {
        $property->number();
    }
}
