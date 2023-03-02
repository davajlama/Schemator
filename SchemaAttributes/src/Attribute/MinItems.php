<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaAttributes\Attribute;

use Attribute;
use Davajlama\Schemator\Schema\Property;
use Davajlama\Schemator\SchemaAttributes\PropertyAttribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
final class MinItems implements PropertyAttribute
{
    private int $minItems;

    public function __construct(int $minItems)
    {
        $this->minItems = $minItems;
    }

    public function apply(Property $property): void
    {
        $property->minItems($this->minItems);
    }
}