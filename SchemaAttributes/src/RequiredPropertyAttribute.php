<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaAttributes;

use Davajlama\Schemator\Schema\Property;

final class RequiredPropertyAttribute implements PropertyAttribute
{
    public function apply(Property $property): void
    {
        $property->required(true);
    }
}
