<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaAttributes;

use Davajlama\Schemator\Schema\Schema;

final class SchemaBuilder
{
    private PropertiesLoader $propertiesLoader;

    private AttributesLoader $attributesLoader;

    public function __construct()
    {
        $this->propertiesLoader = new PropertiesLoader();
        $this->attributesLoader = new AttributesLoader($this);
    }

    public function build(string $class): Schema
    {
        $schema = new Schema($class);

        $properties = $this->propertiesLoader->loadFromConstructor($class);

        foreach ($properties as $property) {
            $attributes = $this->attributesLoader->loadFromParameter($property);

            $prop = $schema->prop($property->getName());
            foreach ($attributes as $attribute) {
                $attribute->apply($prop);
            }
        }

        return $schema;
    }
}