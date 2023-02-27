<?php

namespace Davajlama\Schemator\SchemaAttributes;

use Davajlama\Schemator\Schema\Property;

interface PropertyAttribute
{
    public function apply(Property $property): void;
}