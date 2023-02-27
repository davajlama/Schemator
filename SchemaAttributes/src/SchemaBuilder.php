<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaAttributes;

use Davajlama\Schemator\Schema\Schema;

final class SchemaBuilder
{
    private PropertiesLoader $propertiesLoader;
    private AttributesLoader $attributesLoader;

    public function __construct(PropertiesLoader $propertiesLoader, AttributesLoader $attributesLoader)
    {
        $this->propertiesLoader = $propertiesLoader;
        $this->attributesLoader = $attributesLoader;
    }

    public function build(string $class): Schema
    {
        $schema = new Schema($class);

        $properties = $this->propertiesLoader->load($class);
        foreach ($properties as $property) {
            $attributes = $this->attributesLoader->load($property);

            $prop = $schema->prop($property->getName());
            foreach ($attributes as $attribute) {
                $attribute->apply($prop);
            }
        }

        return $schema;
    }
}