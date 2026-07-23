<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi\Tests;

use Davajlama\Schemator\Demo\BookStore\Schema\Contact;
use Davajlama\Schemator\OpenApi\Api;
use Davajlama\Schemator\OpenApi\Api\SecurityScheme;
use Davajlama\Schemator\OpenApi\OpenApiBuilder;
use LogicException;
use PHPUnit\Framework\TestCase;

final class SecuritySchemeTest extends TestCase
{
    public function testApiKeySchemeIsBuiltIntoComponents(): void
    {
        $api = new Api();
        $api->securityScheme('ApiKeyAuth', SecurityScheme::apiKey('x-apikey')->description('Api key description.'));
        $api->security('ApiKeyAuth');

        $spec = (new OpenApiBuilder())->build($api);

        self::assertSame([
            'securitySchemes' => [
                'ApiKeyAuth' => [
                    'type' => 'apiKey',
                    'description' => 'Api key description.',
                    'name' => 'x-apikey',
                    'in' => 'header',
                ],
            ],
            'schemas' => [],
        ], $spec['components']);

        self::assertSame([['ApiKeyAuth' => []]], $spec['security']);
    }

    public function testSecuritySchemesAreMergedWithComponentSchemas(): void
    {
        $api = new Api();
        $api->securityScheme('ApiKeyAuth', SecurityScheme::apiKey('x-apikey'));
        $api->post('/contacts')->jsonRequestBody(Contact::class);

        $spec = (new OpenApiBuilder())->build($api);

        self::assertIsArray($spec['components']);
        self::assertArrayHasKey('securitySchemes', $spec['components']);
        self::assertArrayHasKey('schemas', $spec['components']);
        self::assertIsArray($spec['components']['schemas']);
        self::assertArrayHasKey('DavajlamaSchematorDemoBookStoreSchemaContact', $spec['components']['schemas']);
    }

    public function testHttpSchemesAreBuilt(): void
    {
        $api = new Api();
        $api->securityScheme('BasicAuth', SecurityScheme::httpBasic());
        $api->securityScheme('BearerAuth', SecurityScheme::httpBearer('JWT'));

        $spec = $api->build();

        self::assertSame([
            'securitySchemes' => [
                'BasicAuth' => [
                    'type' => 'http',
                    'scheme' => 'basic',
                ],
                'BearerAuth' => [
                    'type' => 'http',
                    'scheme' => 'bearer',
                    'bearerFormat' => 'JWT',
                ],
            ],
        ], $spec['components']);
    }

    public function testMethodSecurityOverride(): void
    {
        $api = new Api();
        $api->security('ApiKeyAuth');
        $api->get('/private')->security('BearerAuth', 'read', 'write');
        $api->get('/public')->withoutSecurity();

        $spec = $api->build();

        self::assertSame([['ApiKeyAuth' => []]], $spec['security']);
        self::assertSame([
            '/private' => [
                'get' => [
                    'security' => [['BearerAuth' => ['read', 'write']]],
                ],
            ],
            '/public' => [
                'get' => [
                    'security' => [],
                ],
            ],
        ], $spec['paths']);
    }

    public function testAddingDuplicateSecuritySchemeThrows(): void
    {
        $api = new Api();
        $api->securityScheme('ApiKeyAuth', SecurityScheme::apiKey('x-apikey'));

        $this->expectException(LogicException::class);
        $api->securityScheme('ApiKeyAuth', SecurityScheme::apiKey('x-apikey'));
    }

    public function testUnsupportedApiKeyLocationThrows(): void
    {
        $this->expectException(LogicException::class);
        SecurityScheme::apiKey('x-apikey', 'body');
    }

    public function testInIsNotSupportedByHttpScheme(): void
    {
        $this->expectException(LogicException::class);
        SecurityScheme::httpBasic()->in(SecurityScheme::IN_HEADER);
    }
}
