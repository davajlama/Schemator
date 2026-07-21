<?php

declare(strict_types=1);

namespace Davajlama\Schemator\JsonSchema\Tests;

use Davajlama\Schemator\JsonSchema\JsonSchemaBuilder;
use Davajlama\Schemator\Schema\Schema;
use PHPUnit\Framework\TestCase;

final class JsonSchemaBuilderTest extends TestCase
{
    public function testReferencePropertyKeepsPropertyLevelDocumentation(): void
    {
        $referenced = new Schema();
        $referenced->prop('street')->string();

        $schema = new Schema();
        $schema->prop('address')
            ->ref($referenced)
            ->title('Address')
            ->description('Delivery address')
            ->example('Main street 1');

        $spec = (new JsonSchemaBuilder())->build($schema);

        $properties = $spec['properties'] ?? null;
        self::assertIsArray($properties);
        $address = $properties['address'] ?? null;
        self::assertIsArray($address);
        self::assertSame('Address', $address['title'] ?? null);
        self::assertSame('Delivery address', $address['description'] ?? null);
        self::assertSame('Main street 1', $address['example'] ?? null);
        self::assertArrayHasKey('street', $address['properties'] ?? []);
    }

    public function testReferencePropertyWithoutDocumentationStaysBare(): void
    {
        $referenced = new Schema();
        $referenced->prop('street')->string();

        $schema = new Schema();
        $schema->prop('address')->ref($referenced);

        $spec = (new JsonSchemaBuilder())->build($schema);

        $properties = $spec['properties'] ?? null;
        self::assertIsArray($properties);
        $address = $properties['address'] ?? null;
        self::assertIsArray($address);
        self::assertArrayNotHasKey('title', $address);
        self::assertArrayNotHasKey('description', $address);
        self::assertArrayNotHasKey('example', $address);
    }

    public function testScalarPropertyDocumentationIsUnchanged(): void
    {
        $schema = new Schema();
        $schema->prop('name')->string()->description('Full name')->example('John Doe');

        $spec = (new JsonSchemaBuilder())->build($schema);

        $properties = $spec['properties'] ?? null;
        self::assertIsArray($properties);
        $name = $properties['name'] ?? null;
        self::assertIsArray($name);
        self::assertSame('Full name', $name['description'] ?? null);
        self::assertSame('John Doe', $name['example'] ?? null);
    }
}
