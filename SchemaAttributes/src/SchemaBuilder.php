<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaAttributes;

use Davajlama\Schemator\SanitizerAttributes\ReflectionVariable;
use Davajlama\Schemator\Schema\Rules\Type\BoolType;
use Davajlama\Schemator\Schema\Rules\Type\FloatType;
use Davajlama\Schemator\Schema\Rules\Type\IntegerType;
use Davajlama\Schemator\Schema\Rules\Type\StringType;
use Davajlama\Schemator\Schema\Schema;
use LogicException;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;
use ReflectionUnionType;

use function class_implements;
use function count;
use function in_array;
use function sprintf;

/**
 * @template T of object
 */
class SchemaBuilder
{
    /**
     * @param class-string<T> $className
     */
    public function build(string $className): Schema
    {
        $rfc = new ReflectionClass($className);

        $schema = new Schema($className);
        foreach ($this->loadFromClass($rfc) as $rule) {
            $rule->apply($schema);
        }

        foreach ($this->loadPropertiesFromClass($rfc) as $reflectionVariable) {
            $attributes = $this->loadFromProperty($reflectionVariable);

            $prop = $schema->prop($reflectionVariable->getName());

            if ($reflectionVariable->hasDefaultValue()) {
                $prop->required(false);
            }

            foreach ($attributes as $attribute) {
                $attribute->apply($prop);
            }
        }

        return $schema;
    }

    /**
     * @param ReflectionClass<T> $class
     * @return SchemaAttribute[]
     */
    private function loadFromClass(ReflectionClass $class): array
    {
        $attributes = [];
        foreach ($class->getAttributes() as $attribute) {
            if (in_array(SchemaAttribute::class, class_implements($attribute->getName()), true)) {
                /** @var SchemaAttribute $attributeRule */
                $attributeRule = $attribute->newInstance();

                $attributes[] = $attributeRule;
            }
        }

        return $attributes;
    }

    /**
     * @return PropertyAttribute[]
     */
    private function loadFromProperty(ReflectionVariable $property): array
    {
        $originType = $property->getType();
        if ($originType === null) {
            throw new LogicException('Untyped properties not supported.');
        }

        $types = [$originType];
        if ($originType instanceof ReflectionUnionType) {
            $types = $originType->getTypes();
        }

        $attributes = [];
        if ($originType->allowsNull()) {
            $attributes[] = new NullablePropertyAttribute();
        }

        foreach ($types as $type) {
            /** @var ReflectionNamedType $type */
            if ($type->getName() !== 'null' && $type->getName() !== 'array') {
                $attributes[] = $this->loadFromType($type);
            }
        }

        foreach ($property->getAttributes() as $attribute) {
            if (in_array(PropertyAttribute::class, class_implements($attribute->getName()), true)) {
                /** @var PropertyAttribute $attributeRule */
                $attributeRule = $attribute->newInstance();

                $attributes[] = $attributeRule;
            }
        }

        if (count($attributes) === 0) {
            throw new LogicException(sprintf('No attributes founds for [%s].', $property->getName()));
        }

        return $attributes;
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

    private function loadFromType(ReflectionNamedType $type): PropertyAttribute
    {
        switch ($type->getName()) {
            case 'DateTimeInterface':
            case 'string':
                return new TypePropertyAttribute(new StringType());
            case 'int':
                return new TypePropertyAttribute(new IntegerType());
            case 'bool':
                return new TypePropertyAttribute(new BoolType());
            case 'float':
                return new TypePropertyAttribute(new FloatType());
            default:
                /** @var class-string<T> $className */
                $className = $type->getName();
                return new ReferencedPropertyAttribute($this->build($className));
        }
    }
}
