<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SanitizerAttributes\Tests;

use Davajlama\Schemator\SanitizerAttributes\SanitizerBuilder;
use Davajlama\Schemator\SanitizerAttributes\Tests\Fixtures\Author;
use Davajlama\Schemator\SanitizerAttributes\Tests\Fixtures\Book;
use PHPUnit\Framework\TestCase;

final class SanitizerBuilderTest extends TestCase
{
    public function testTrimFilter(): void
    {
        $authorPayload = [
            'firstname' => ' Dave ',
            'lastname' => ' Lister ',
        ];

        $sanitizer = (new SanitizerBuilder())->build(Author::class);
        $data = $sanitizer->sanitize($authorPayload);

        self::assertSame('Dave', $data['firstname']);
        self::assertSame('Lister', $data['lastname']);

        $bookPayload = [
            'name' => ' Red Dwarf ',
            'author' => $authorPayload,
            'secondAuthor' => $authorPayload,
        ];

        $sanitizer = (new SanitizerBuilder())->build(Book::class);
        $data = $sanitizer->sanitize($bookPayload);

        self::assertSame('Red Dwarf', $data['name']);
        self::assertSame('Dave', $data['author']['firstname']);
        self::assertSame('Lister', $data['author']['lastname']);
        self::assertSame('Dave', $data['secondAuthor']['firstname']);
        self::assertSame('Lister', $data['secondAuthor']['lastname']);
    }
}
