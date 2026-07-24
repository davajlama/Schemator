<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi\Tests;

use Davajlama\Schemator\OpenApi\Api;
use Davajlama\Schemator\OpenApi\OpenApiBuilder;
use Davajlama\Schemator\Schema\Schema;
use PHPUnit\Framework\TestCase;

final class OpenApiBuilderTest extends TestCase
{
    public function testComponentSchemasHaveExplicitObjectType(): void
    {
        $address = new Schema('Address');
        $address->prop('street')->string();

        $order = new Schema('Order');
        $order->prop('address')->ref($address);
        $order->prop('packages')->arrayOf($address);

        $component = $this->buildComponent($order);

        self::assertSame('object', $component['type'] ?? null);

        $properties = $component['properties'] ?? [];
        self::assertSame('object', $properties['address']['type'] ?? null);
        self::assertSame('array', $properties['packages']['type'] ?? null);
        self::assertSame('object', $properties['packages']['items']['type'] ?? null);
    }

    public function testNullableTypedPropertyIsConvertedToNullableFlag(): void
    {
        $order = new Schema('Order');
        $order->prop('note')->string()->nullable();

        $component = $this->buildComponent($order);

        $note = $component['properties']['note'] ?? [];
        self::assertSame('string', $note['type'] ?? null);
        self::assertTrue($note['nullable'] ?? null);
    }

    public function testNullablePropertyWithoutTypeOmitsTypeKeyword(): void
    {
        $order = new Schema('Order');
        $order->prop('payload')->nullable()->description('Arbitrary payload');

        $component = $this->buildComponent($order);

        $payload = $component['properties']['payload'] ?? null;
        self::assertIsArray($payload);
        self::assertArrayNotHasKey('type', $payload);
        self::assertArrayNotHasKey('nullable', $payload);
        self::assertSame('Arbitrary payload', $payload['description'] ?? null);
    }

    /**
     * @return mixed[]
     */
    private function buildComponent(Schema $schema): array
    {
        $api = new Api();
        $api->post('/orders')->jsonRequestBody($schema);

        $spec = (new OpenApiBuilder())->build($api);

        $component = $spec['components']['schemas'][(string) $schema->getName()] ?? null;
        self::assertIsArray($component);

        return $component;
    }
}
