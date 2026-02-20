<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaAttributes\Resolver;

use BackedEnum;
use Davajlama\Schemator\Schema\Rules\Enum;
use Davajlama\Schemator\SchemaAttributes\PropertyAttribute;
use Davajlama\Schemator\SchemaAttributes\PropertyTypeResolver;
use Davajlama\Schemator\SchemaAttributes\TypePropertyAttribute;
use ReflectionNamedType;

use function class_exists;
use function class_implements;
use function in_array;

class BackedEnumTypeResolver implements PropertyTypeResolver
{
    public function support(ReflectionNamedType $type): bool
    {
        return class_exists($type->getName()) && in_array(BackedEnum::class, (array) class_implements($type->getName()), true);
    }

    public function resolve(ReflectionNamedType $type): PropertyAttribute
    {
        /** @var BackedEnum $enumClass */
        $enumClass = $type->getName();

        $values = [];
        foreach ($enumClass::cases() as $case) {
            $values[] = $case->value;
        }

        return new TypePropertyAttribute(new Enum($values));
    }
}
