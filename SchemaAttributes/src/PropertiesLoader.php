<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaAttributes;

use ReflectionClass;
use ReflectionProperty;

final class PropertiesLoader
{
    /**
     * @return ReflectionProperty[]
     */
    public function load($class): array
    {
        $rfc = new ReflectionClass($class);
        return $rfc->getProperties(ReflectionProperty::IS_PUBLIC);
    }
}