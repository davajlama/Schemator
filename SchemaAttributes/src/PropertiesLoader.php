<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaAttributes;

use LogicException;
use ReflectionClass;
use ReflectionParameter;

final class PropertiesLoader
{
    /**
     * @return ReflectionParameter[]
     */
    public function loadFromConstructor(string $class): array
    {
        $rfc = new ReflectionClass($class);
        $constructor = $rfc->getConstructor();
        if ($constructor === null) {
            throw new LogicException('Unable to load properties from class without constructor.');
        }

        return $constructor->getParameters();
    }
}