<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaAttributes;

use Davajlama\Schemator\Schema\Property;

interface PropertyAttribute
{
    public function apply(Property $property): void;
}
