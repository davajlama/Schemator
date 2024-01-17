<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SanitizerAttributes\Tests;

use Davajlama\Schemator\SanitizerAttributes\SanitizerBuilder;
use Davajlama\Schemator\SchemaAttributes\Tests\Fixtures\Author;
use Davajlama\Schemator\SchemaAttributes\Tests\Fixtures\Book;
use PHPUnit\Framework\TestCase;

final class SanitizerBuilderTest extends TestCase
{
    public function testTrimFilter(): void
    {
        $payload = [
            'firstname' => ' Dave ',
            'lastname' => ' Lister ',
        ];

        $sanitizer = (new SanitizerBuilder())->build(Author::class);
        $data = $sanitizer->sanitize($payload);

        self::assertSame('Dave', $data['firstname']);
        self::assertSame('Lister', $data['lastname']);

        $payload = [
            'name' => ' Red Dwarf ',
        ];

        $sanitizer = (new SanitizerBuilder())->build(Book::class);
        $data = $sanitizer->sanitize($payload);

        self::assertSame('Red Dwarf', $data['name']);
    }
}
