<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaAttributes\Attribute;

use Attribute;
use Davajlama\Schemator\Schema\Property;
use Davajlama\Schemator\SchemaAttributes\PropertyAttribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class AnyOf implements PropertyAttribute
{
    private string $typeProperty;

    /**
     * @var array<string, string>
     */
    private array $mapping;

    /**
     * @param array<string, string> $mapping
     */
    public function __construct(string $typeProperty, array $mapping)
    {
        $this->typeProperty = $typeProperty;
        $this->mapping = $mapping;
    }

    public function apply(Property $property): void
    {
        $property->anyOf($this->typeProperty, $this->mapping);
    }
}
