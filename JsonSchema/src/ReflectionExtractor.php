<?php

declare(strict_types=1);

namespace Davajlama\Schemator\JsonSchema;

use ReflectionClass;

final class ReflectionExtractor
{
    public static function getProperty(object $object, string $name): mixed
    {
        $rc = new ReflectionClass($object);
        $rp = $rc->getProperty($name);

        $rp->setAccessible(true);

        return $rp->getValue($object);
    }
}
