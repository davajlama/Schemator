<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaAttributes\Attribute;

use Attribute;
use Davajlama\Schemator\Schema\Property;
use Davajlama\Schemator\SchemaAttributes\PropertyAttribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Required implements PropertyAttribute
{
    private bool $required;

    public function __construct(bool $required = true)
    {
        $this->required = $required;
    }

    public function apply(Property $property): void
    {
        $property->required($this->required);
    }
}
