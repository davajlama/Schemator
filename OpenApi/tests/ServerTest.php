<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi\Tests;

use Davajlama\Schemator\OpenApi\Api;
use Davajlama\Schemator\OpenApi\Api\Server;
use LogicException;
use PHPUnit\Framework\TestCase;

final class ServerTest extends TestCase
{
    public function testServersAreBuilt(): void
    {
        $api = new Api();
        $api->server('https://api.zaslat.cz/v1')->description('Production server');
        $api->server('https://sandbox.zaslat.cz/v1')->description('Sandbox server');

        $spec = $api->build();

        self::assertSame([
            [
                'url' => 'https://api.zaslat.cz/v1',
                'description' => 'Production server',
            ],
            [
                'url' => 'https://sandbox.zaslat.cz/v1',
                'description' => 'Sandbox server',
            ],
        ], $spec['servers']);
    }

    public function testServersAreOmittedWhenNotDefined(): void
    {
        self::assertArrayNotHasKey('servers', (new Api())->build());
    }

    public function testServerByUrlIsReused(): void
    {
        $api = new Api();

        self::assertSame($api->server('https://api.zaslat.cz/v1'), $api->server('https://api.zaslat.cz/v1'));
    }

    public function testAddingDuplicateServerThrows(): void
    {
        $api = new Api();
        $api->server('https://api.zaslat.cz/v1');

        $this->expectException(LogicException::class);
        $api->addServer(new Server('https://api.zaslat.cz/v1'));
    }
}
