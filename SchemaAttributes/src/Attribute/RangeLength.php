<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaAttributes\Attribute;

use Attribute;
use Davajlama\Schemator\Schema\Property;
use Davajlama\Schemator\SchemaAttributes\PropertyAttribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class RangeLength implements PropertyAttribute
{
    private int $minLength;

    private int $maxLength;

    public function __construct(int $minLength, int $maxLength)
    {
        $this->minLength = $minLength;
        $this->maxLength = $maxLength;
    }

    public function apply(Property $property): void
    {
        $property->minLength($this->minLength)->maxLength($this->maxLength);
    }
}
