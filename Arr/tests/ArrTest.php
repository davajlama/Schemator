<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Arr\Tests;

use Davajlama\Schemator\Arr\Arr;
use Davajlama\Schemator\Arr\Exception\ArrException;
use LogicException;
use PHPUnit\Framework\TestCase;

final class ArrTest extends TestCase
{
    private const PAYLOAD = [
        'name' => 'Soap',
        'note' => '',
        'attributes' => [
            ['name' => 'Test01', 'description' => 'desc1', 'value' => 100],
            ['name' => 'Test02', 'description' => 'desc2', 'value' => 200],
        ],
        'manufacturer' => [
            'company' => 'Foo s.r.o.',
            'person' => [
                'firstname' => 'Dave',
                'lastname' => 'Lister',
            ],
        ],
    ];

    private Arr $arr;

    protected function setUp(): void
    {
        $this->arr = Arr::create(self::PAYLOAD, true);
    }

    public function testAll(): void
    {
        $arr = $this->arr->me('attributes')->all('name', '=', 'Test01');
        self::assertSame(1, $arr->count());
        self::assertSame('desc1', $arr->first()->value('description'));

        $arr = $this->arr->me('attributes')->all('name', '!=', 'Test01');
        self::assertSame(1, $arr->count());
        self::assertSame('desc2', $arr->first()->value('description'));

        $arr = $this->arr->me('attributes')->all('value', '>', 50);
        self::assertSame(2, $arr->count());

        $arr = $this->arr->me('attributes')->all('value', '<', 300);
        self::assertSame(2, $arr->count());

        $arr = $this->arr->me('attributes')->all('value', '<', 200);
        self::assertSame(1, $arr->count());

        self::expectException(LogicException::class);
        self::expectExceptionMessage('Unsupported operator.');

        $this->arr->me('attributes')->all('value', 'lt', 200);
    }

    public function testOne(): void
    {
        $arr = $this->arr->me('attributes')->one('name', '=', 'Test01');
        self::assertSame('desc1', $arr?->value('description'));

        $arr = $this->arr->me('attributes')->one('name', '=', 'Test02');
        self::assertSame('desc2', $arr?->value('description'));

        $arr = $this->arr->me('attributes')->one('name', '=', 'Test03');
        self::assertNull($arr);
    }

    public function testThe(): void
    {
        self::expectException(ArrException::class);
        self::expectErrorMessage('Property is not an array.');

        $this->arr->me('attributes')->the('name', '=', 'Test03');
    }
}
