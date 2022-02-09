<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Tests;

use Davajlama\Schemator\Filter;
use PHPUnit\Framework\TestCase;

final class FilterTest extends TestCase
{
    public function testSuccessFilter(): void
    {
        $filter = new Filter();
        $filter->property('age')->default(21);
        $filter->property('firstname')->trim();
        $filter->property('surname')->default('Lister')->trim();
        $filter->property('type')->upper();

        $payload = ['firstname' => '  Dave  ', 'type' => 'foo'];

        $data = $filter->apply($payload);
        $expectedData = [
            'age' => 21,
            'firstname' => 'Dave',
            'surname' => 'Lister',
            'type' => 'FOO',
        ];

        self::assertEqualsCanonicalizing($expectedData, $data);
    }

    public function testSuccessMultipleFilter(): void
    {
        $filter = new Filter();
        $filter->properties('foo', 'bar', 'tar')->trim();

        $payload = ['foo' => ' 1', 'bar' => '1 ', 'tar' => ' 1 '];
        $data = $filter->apply($payload);

        $expected = ['foo' => '1', 'bar' => '1', 'tar' => '1'];
        self::assertEquals($expected, $data);
    }
}
