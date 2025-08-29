<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaAttributes\Attribute;

use Attribute;
use Davajlama\Schemator\Schema\Property;
use Davajlama\Schemator\SchemaAttributes\PropertyAttribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
final class Example implements PropertyAttribute
{
    private string|int|float|bool|null $value;

    public function __construct(string|int|float|bool|null $value)
    {
        $this->value = $value;
    }

    public function apply(Property $property): void
    {
        $property->example($this->value);
    }
}
