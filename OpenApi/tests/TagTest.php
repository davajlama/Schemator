<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi\Tests;

use Davajlama\Schemator\OpenApi\Api;
use Davajlama\Schemator\OpenApi\Api\Tag;
use LogicException;
use PHPUnit\Framework\TestCase;

final class TagTest extends TestCase
{
    public function testTagsAndExternalDocsAreBuilt(): void
    {
        $api = new Api();
        $api->externalDocs('https://docs.zaslat.cz')->description('Full documentation');
        $api->tag('shipments')->description('Shipments management');
        $api->tag('pickups')->externalDocs('https://docs.zaslat.cz/pickups');

        $spec = $api->build();

        self::assertSame([
            'url' => 'https://docs.zaslat.cz',
            'description' => 'Full documentation',
        ], $spec['externalDocs']);

        self::assertSame([
            [
                'name' => 'shipments',
                'description' => 'Shipments management',
            ],
            [
                'name' => 'pickups',
                'externalDocs' => ['url' => 'https://docs.zaslat.cz/pickups'],
            ],
        ], $spec['tags']);
    }

    public function testTagByNameIsReused(): void
    {
        $api = new Api();

        self::assertSame($api->tag('shipments'), $api->tag('shipments'));
    }

    public function testAddingDuplicateTagThrows(): void
    {
        $api = new Api();
        $api->tag('shipments');

        $this->expectException(LogicException::class);
        $api->addTag(new Tag('shipments'));
    }
}
