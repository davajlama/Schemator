<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaAttributes;

final class PropertyAttributes
{
    private bool $nullable;

    /**
     * @var \ReflectionNamedType[]
     */
    private array $attributes;

    /**
     * @param \ReflectionNamedType[] $attributes
     */
    public function __construct(bool $nullable, array $attributes)
    {
        $this->nullable = $nullable;
        $this->attributes = $attributes;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    /**
     * @return \ReflectionNamedType[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}