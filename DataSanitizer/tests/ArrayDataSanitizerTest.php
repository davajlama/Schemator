<?php

declare(strict_types=1);

namespace Davajlama\Schemator\DataSanitizer\Tests;

use Davajlama\Schemator\DataSanitizer\ArrayDataSanitizer;
use PHPUnit\Framework\TestCase;

final class ArrayDataSanitizerTest extends TestCase
{
    public function testSanitizeSimpleArray(): void
    {
        $payload = [
            'name' => ' Dave   Lister ',
            'firstname' => ' Dave ',
            'surname' => '   ',
            'store' => '100',
            'price' => '50,55',
            'author' => [
                'name' => ' Rimmer ',
            ],
            'stringA' => ' A ',
            'stringB' => ' B ',
            'stringedInt' => '100',
            'stringedFloat' => '1.1',
        ];

        $sanitizer = new ArrayDataSanitizer();
        $sanitizer->props('name')->spaceless();
        $sanitizer->props('firstname')->trim();
        $sanitizer->props('surname')->trim()->emptyStringToNull();
        $sanitizer->props('store')->numericToInt();
        $sanitizer->props('price')->replace(',', '.')->numericToFloat();
        $sanitizer->props('age')->defaultIfNotExists(35);
        $sanitizer->ref('author')->props('name')->trim();
        $sanitizer->props('stringA', 'stringB')->trim();
        $sanitizer->props('stringedInt')->stringedNumberToInt();
        $sanitizer->props('stringedFloat')->stringedNumberToFloat();

        $sanitizedPayload = $sanitizer->sanitize($payload);

        self::assertArrayHasKey('name', $sanitizedPayload);
        self::assertSame('DaveLister', $sanitizedPayload['name']);

        self::assertArrayHasKey('firstname', $sanitizedPayload);
        self::assertSame('Dave', $sanitizedPayload['firstname']);

        self::assertArrayHasKey('surname', $sanitizedPayload);
        self::assertSame(null, $sanitizedPayload['surname']);

        self::assertArrayHasKey('store', $sanitizedPayload);
        self::assertSame(100, $sanitizedPayload['store']);

        self::assertArrayHasKey('price', $sanitizedPayload);
        self::assertSame(50.55, $sanitizedPayload['price']);

        self::assertArrayHasKey('age', $sanitizedPayload);
        self::assertSame(35, $sanitizedPayload['age']);

        self::assertArrayHasKey('author', $sanitizedPayload);

        self::assertArrayHasKey('name', $sanitizedPayload['author']);
        self::assertSame('Rimmer', $sanitizedPayload['author']['name']);

        self::assertArrayHasKey('stringA', $sanitizedPayload);
        self::assertSame('A', $sanitizedPayload['stringA']);
        self::assertArrayHasKey('stringB', $sanitizedPayload);
        self::assertSame('B', $sanitizedPayload['stringB']);

        self::assertArrayHasKey('stringedInt', $sanitizedPayload);
        self::assertSame(100, $sanitizedPayload['stringedInt']);

        self::assertArrayHasKey('stringedFloat', $sanitizedPayload);
        self::assertSame(1.1, $sanitizedPayload['stringedFloat']);
    }
}
