<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaAttributes\Attribute;

use Attribute;
use Davajlama\Schemator\Schema\Property;
use Davajlama\Schemator\SchemaAttributes\PropertyAttribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Enum implements PropertyAttribute
{
    /**
     * @var scalar[]
     */
    private array $values;

    /**
     * @param scalar[] $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }
    public function apply(Property $property): void
    {
        $property->enum($this->values);
    }
}