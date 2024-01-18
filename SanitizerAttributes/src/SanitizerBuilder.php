<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SanitizerAttributes;

use Davajlama\Schemator\DataSanitizer\ArrayDataSanitizer;
use LogicException;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;
use ReflectionUnionType;

use function array_map;
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

        $variables = $this->loadPropertiesFromClass($rfc);
        $variableNames = array_map(static fn(ReflectionVariable $v) => $v->getName(), $variables);

        foreach ($this->loadFromClass($rfc) as $attribute) {
            $group = $sanitizer->props(...$variableNames);
            $attribute->apply($group);
        }

        foreach ($variables as $variable) {
            if ($this->isScalarProperty($variable)) {
                $attributes = $this->loadFromProperty($variable);
                foreach ($attributes as $attribute) {
                    $group = $sanitizer->props($variable->getName());
                    $attribute->apply($group);
                }
            } elseif ($this->isSingleObjectProperty($variable)) {
                    $type = $variable->getType();
                if ($type instanceof ReflectionNamedType) {
                    /** @var class-string<T> $refClassName */
                    $refClassName = $type->getName();
                    $sanitizer->ref($variable->getName(), $this->build($refClassName));
                }
            } else {
                throw new LogicException('Multi object properties not supported.');
            }
        }

        return $sanitizer;
    }

    /**
     * @param ReflectionClass<T> $class
     * @return ReflectionVariable[]
     */
    private function loadPropertiesFromClass(ReflectionClass $class): array
    {
        $variables = [];
        $constructor = $class->getConstructor();
        if ($constructor !== null) {
            foreach ($constructor->getParameters() as $parameter) {
                $variables[] = new ReflectionVariable($parameter);
            }
        } else {
            foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
                $variables[] = new ReflectionVariable($property);
            }
        }

        return $variables;
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
    private function loadFromProperty(ReflectionVariable $variable): array
    {
        $attributes = [];
        foreach ($variable->getAttributes() as $attribute) {
            if (in_array(GroupAttribute::class, class_implements($attribute->getName()), true)) {
                /** @var GroupAttribute $filter */
                $filter = $attribute->newInstance();

                $attributes[] = $filter;
            }
        }

        return $attributes;
    }

    private function isScalarProperty(ReflectionVariable $variable): bool
    {
        $originType = $variable->getType();
        if ($originType === null) {
            throw new LogicException('Untyped properties not supported.');
        }

        $types = [$originType];
        if ($originType instanceof ReflectionUnionType) {
            $types = $originType->getTypes();
        }

        foreach ($types as $type) {
            if (!in_array($type->getName(), ['string', 'int', 'bool', 'float', 'array', 'null'], true)) {
                return false;
            }
        }

        return true;
    }

    private function isSingleObjectProperty(ReflectionVariable $variable): bool
    {
        if ($this->isScalarProperty($variable)) {
            return false;
        }

        $originType = $variable->getType();

        return $originType instanceof ReflectionUnionType === false;
    }
}
