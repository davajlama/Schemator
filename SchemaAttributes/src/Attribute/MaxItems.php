<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaAttributes\Attribute;

use Attribute;
use Davajlama\Schemator\Schema\Property;
use Davajlama\Schemator\SchemaAttributes\PropertyAttribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
final class MaxItems implements PropertyAttribute
{
    private int $maxItems;

    public function __construct(int $maxItems)
    {
        $this->maxItems = $maxItems;
    }

    public function apply(Property $property): void
    {
        $property->maxItems($this->maxItems);
    }
}