<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaAttributes\Attribute;

use Attribute;
use Davajlama\Schemator\Schema\Property;
use Davajlama\Schemator\SchemaAttributes\PropertyAttribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class MinLength implements PropertyAttribute
{
    private int $minLength;

    public function __construct(int $minLength)
    {
        $this->minLength = $minLength;
    }

    public function apply(Property $property): void
    {
        $property->minLength($this->minLength);
    }
}
