<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaAttributes\Attribute;

use Attribute;
use Davajlama\Schemator\Schema\Property;
use Davajlama\Schemator\SchemaAttributes\PropertyAttribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
final class Min implements PropertyAttribute
{
    private float $min;

    public function __construct(float $min)
    {
        $this->min = $min;
    }

    public function apply(Property $property): void
    {
        $property->min($this->min);
    }
}
