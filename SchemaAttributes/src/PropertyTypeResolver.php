<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaAttributes;

use ReflectionNamedType;

interface PropertyTypeResolver
{
    public function support(ReflectionNamedType $type): bool;

    public function resolve(ReflectionNamedType $type): PropertyAttribute;
}
