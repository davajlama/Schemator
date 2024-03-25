<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaAttributes\Attribute;

use Attribute;
use Davajlama\Schemator\Schema\Property;
use Davajlama\Schemator\SchemaAttributes\PropertyAttribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
final class Length implements PropertyAttribute
{
    private int $length;

    public function __construct(int $length)
    {
        $this->length = $length;
    }

    public function apply(Property $property): void
    {
        $property->length($this->length);
    }
}
