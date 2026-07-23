<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi\Tests;

use Davajlama\Schemator\OpenApi\Api;
use PHPUnit\Framework\TestCase;

final class MethodTest extends TestCase
{
    public function testOperationIdAndDeprecatedAreBuilt(): void
    {
        $api = new Api();
        $api->get('/shipments')
            ->operationId('listShipments')
            ->deprecated();

        $spec = $api->build();

        self::assertSame([
            '/shipments' => [
                'get' => [
                    'operationId' => 'listShipments',
                    'deprecated' => true,
                ],
            ],
        ], $spec['paths']);
    }

    public function testOperationIdAndDeprecatedAreOmittedByDefault(): void
    {
        $spec = (new Api())->get('/shipments')->build();

        self::assertSame(['get' => []], $spec);
    }
}
