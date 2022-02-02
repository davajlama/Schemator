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

        $payload = ['firstname' => '  Dave  '];

        $data = $filter->apply($payload);
        $expectedData = [
            'age' => 21,
            'firstname' => 'Dave',
            'surname' => 'Lister',
        ];

        self::assertEqualsCanonicalizing($expectedData, $data);
    }

}