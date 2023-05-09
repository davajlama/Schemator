<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaAttributes;

use Davajlama\Schemator\Schema\Property;
use Davajlama\Schemator\Schema\RuleInterface;

final class TypePropertyAttribute implements PropertyAttribute
{
    private RuleInterface $rule;

    public function __construct(RuleInterface $rule)
    {
        $this->rule = $rule;
    }

    public function apply(Property $property): void
    {
        $property->rule($this->rule);
    }
}
