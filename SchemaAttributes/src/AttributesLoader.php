<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaAttributes;

use Davajlama\Schemator\Schema\Rules\Type\BoolType;
use Davajlama\Schemator\Schema\Rules\Type\FloatType;
use Davajlama\Schemator\Schema\Rules\Type\IntegerType;
use Davajlama\Schemator\Schema\Rules\Type\StringType;
use LogicException;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionProperty;
use ReflectionUnionType;

use function class_implements;
use function in_array;
use function var_dump;

final class AttributesLoader
{
    private SchemaBuilder $schemaBuilder;

    public function __construct(SchemaBuilder $schemaBuilder)
    {
        $this->schemaBuilder = $schemaBuilder;
    }

    /**
     * @return PropertyAttribute[]
     */
    public function loadFromParameter(ReflectionParameter $property): array
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
            if ($type->getName() !== 'null' && $type->getName() !== 'array') {
                $attributes[] = $this->loadTypeAttributes($type);
            }
        }

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
    private function loadTypeAttributes(ReflectionNamedType $type): PropertyAttribute
    {
        return match ($type->getName()) {
            'string' => new TypePropertyAttribute(new StringType()),
            'int' => new TypePropertyAttribute(new IntegerType()),
            'bool' => new TypePropertyAttribute(new BoolType()),
            'float' => new TypePropertyAttribute(new FloatType()),
            default => new ReferencedPropertyAttribute($this->schemaBuilder->build($type->getName())),
        };
    }

    private function typeToAttribute(ReflectionNamedType $type): PropertyAttribute
    {
        var_dump($type->getName());
        return new NullablePropertyAttribute();
    }
}
