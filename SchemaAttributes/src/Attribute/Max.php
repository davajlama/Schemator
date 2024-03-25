<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaAttributes\Attribute;

use Attribute;
use Davajlama\Schemator\Schema\Property;
use Davajlama\Schemator\SchemaAttributes\PropertyAttribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
final class Max implements PropertyAttribute
{
    private float $max;

    public function __construct(float $max)
    {
        $this->max = $max;
    }

    public function apply(Property $property): void
    {
        $property->max($this->max);
    }
}
