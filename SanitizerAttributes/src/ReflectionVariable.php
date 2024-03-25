<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SanitizerAttributes;

use ReflectionAttribute;
use ReflectionParameter;
use ReflectionProperty;
use ReflectionType;

final class ReflectionVariable
{
    private ReflectionProperty|ReflectionParameter $property;

    public function __construct(ReflectionParameter|ReflectionProperty $property)
    {
        $this->property = $property;
    }

    public function getName(): string
    {
        return $this->property->getName();
    }

    /**
     * @return ReflectionAttribute<object>[]
     */
    public function getAttributes(): array
    {
        return $this->property->getAttributes();
    }

    public function getType(): ReflectionType|null
    {
        return $this->property->getType();
    }

    public function hasDefaultValue(): bool
    {
        if ($this->property instanceof ReflectionProperty) {
            return $this->property->hasDefaultValue();
        }

        return $this->property->isOptional();
    }
}
