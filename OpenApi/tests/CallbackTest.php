<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi\Tests;

use Davajlama\Schemator\Demo\BookStore\Schema\Contact;
use Davajlama\Schemator\OpenApi\Api;
use Davajlama\Schemator\OpenApi\DecoratorChain;
use Davajlama\Schemator\OpenApi\OpenApiBuilder;
use LogicException;
use PHPUnit\Framework\TestCase;

use function json_encode;

use const JSON_THROW_ON_ERROR;

final class CallbackTest extends TestCase
{
    public function testCallbackIsBuiltOnTheOperation(): void
    {
        $api = new Api();
        $ep = $api->post('/webhook');

        $op = $ep->callback('order.created')->expression('{$request.body#/url}')->post();
        $op->summary('Webhook callback for order.created');
        $op->jsonRequestBody(Contact::class);
        $op->response(200, 'Delivery acknowledged');

        $json = json_encode((new OpenApiBuilder())->build($api), JSON_THROW_ON_ERROR);

        self::assertJson($json);
        self::assertStringContainsString('"callbacks"', $json);
        self::assertStringContainsString('"order.created"', $json);
        self::assertStringContainsString('"{$request.body#\/url}"', $json);
        self::assertStringContainsString('"Webhook callback for order.created"', $json);
    }

    public function testCallbackSchemaIsResolvedIntoComponents(): void
    {
        $api = new Api();
        $ep = $api->post('/webhook');

        $ep->callback('order.created')
            ->expression('{$request.body#/url}')
            ->post()
            ->jsonRequestBody(Contact::class);

        $spec = (new OpenApiBuilder())->build($api);

        // The schema referenced inside the callback is collected into components automatically,
        // exactly like a regular endpoint - no post-processing of the built spec is needed.
        $componentName = 'DavajlamaSchematorDemoBookStoreSchemaContact';

        self::assertIsArray($spec['components']);
        self::assertIsArray($spec['components']['schemas']);
        self::assertArrayHasKey($componentName, $spec['components']['schemas']);
    }

    public function testCallbackByNameIsReused(): void
    {
        $ep = (new Api())->post('/webhook');

        self::assertSame($ep->callback('order.created'), $ep->callback('order.created'));
    }

    public function testAddingDuplicateCallbackThrows(): void
    {
        $ep = (new Api())->post('/webhook');
        $ep->callback('order.created');

        $this->expectException(LogicException::class);
        $ep->addCallback(new Api\Callback('order.created', new DecoratorChain()));
    }
}
