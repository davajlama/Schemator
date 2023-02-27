<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaAttributes\Attribute;

use Davajlama\Schemator\Schema\Property;
use Davajlama\Schemator\Schema\Rules\MaxLength as MaxLengthRule;
use Davajlama\Schemator\SchemaAttributes\PropertyAttribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class MaxLength implements PropertyAttribute
{
    private int $maxLength;

    /**
     * @param int $maxLength
     */
    public function __construct(int $maxLength)
    {
        $this->maxLength = $maxLength;
    }

    public function apply(Property $property): void
    {
        $property->rule(new MaxLengthRule($this->maxLength));
    }
}