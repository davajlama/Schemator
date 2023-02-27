<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaAttributes;

final class AttributesLoader
{
    /**
     * @return PropertyAttribute[]
     */
    public function load(\ReflectionProperty $property): array
    {
        $type = $property->getType();
        if ($type === null) {
            throw new \LogicException('Untyped properties not supported.');
        }

        $attributes = [];
        foreach ($property->getAttributes() as $attribute) {
            if (in_array(PropertyAttribute::class, class_implements($attribute->getName()), true)) {
                /** @var PropertyAttribute $attributeRule */
                $attributeRule = $attribute->newInstance();

                $attributes[] = $attributeRule;
            }
        }

        return $attributes;
    }

    /**
     * @return PropertyAttribute[]
     */
    private function loadTypesAttributes(\ReflectionProperty $property): array
    {


        $attributes = [];

        var_dump($type->allowsNull());

        if ($type->allowsNull()) {
            $attributes[] = new NullablePropertyAttribute();
        }

        if ($type instanceof \ReflectionNamedType) {
            $attributes[] = $this->typeToAttribute($type);
        }

        if ($type instanceof \ReflectionUnionType) {
            foreach ($type->getTypes() as $namedType) {
                $attributes[] = $this->typeToAttribute($namedType);
            }
        }

        return $attributes;
    }

    private function typeToAttribute(\ReflectionNamedType $type): PropertyAttribute
    {

        var_dump($type->getName());
        return new NullablePropertyAttribute();;
    }
}