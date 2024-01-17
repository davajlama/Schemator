<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SanitizerAttributes;

use Davajlama\Schemator\DataSanitizer\ArrayDataSanitizer;
use ReflectionClass;
use ReflectionProperty;

use function class_implements;
use function in_array;

/**
 * @template T of object
 */
final class SanitizerBuilder
{
    /**
     * @param class-string<T> $className
     */
    public function build($className): ArrayDataSanitizer
    {
        $rfc = new ReflectionClass($className);
        $sanitizer = new ArrayDataSanitizer();

        $properties = $this->loadPropertiesFromClass($rfc);
        foreach ($this->loadFromClass($rfc) as $attribute) {
            $group = $sanitizer->props(...$properties);
            $attribute->apply($group);
        }

        foreach ($rfc->getProperties(ReflectionProperty::IS_PUBLIC) as $reflectionProperty) {
            $attributes = $this->loadFromProperty($reflectionProperty);
            foreach ($attributes as $attribute) {
                $group = $sanitizer->props($reflectionProperty->getName());
                $attribute->apply($group);
            }
        }

        return $sanitizer;
    }

    /**
     * @param ReflectionClass<T> $class
     * @return string[]
     */
    private function loadPropertiesFromClass(ReflectionClass $class): array
    {
        $properties = [];
        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $properties[] = $property->getName();
        }

        return $properties;
    }

    /**
     * @param ReflectionClass<T> $class
     * @return GroupAttribute[]
     */
    private function loadFromClass(ReflectionClass $class): array
    {
        $attributes = [];
        foreach ($class->getAttributes() as $attribute) {
            if (in_array(GroupAttribute::class, class_implements($attribute->getName()), true)) {
                /** @var GroupAttribute $filter */
                $filter = $attribute->newInstance();

                $attributes[] = $filter;
            }
        }

        return $attributes;
    }

    /**
     * @return GroupAttribute[]
     */
    private function loadFromProperty(ReflectionProperty $property): array
    {
        $attributes = [];
        foreach ($property->getAttributes() as $attribute) {
            if (in_array(GroupAttribute::class, class_implements($attribute->getName()), true)) {
                /** @var GroupAttribute $filter */
                $filter = $attribute->newInstance();

                $attributes[] = $filter;
            }
        }

        return $attributes;
    }
}
