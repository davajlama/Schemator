<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi\Tests;

use Davajlama\Schemator\Demo\BookStore\Schema\Contact;
use Davajlama\Schemator\OpenApi\PatternComponentNameResolver;
use Davajlama\Schemator\Schema\Schema;
use PHPUnit\Framework\TestCase;

final class PatternComponentNameResolverTest extends TestCase
{
    public function testPatternIsStrippedWithEmptyReplacement(): void
    {
        $resolver = new PatternComponentNameResolver([
            '~^Davajlama\\\\Schemator\\\\Demo\\\\BookStore\\\\Schema\\\\~' => '',
        ]);

        self::assertTrue($resolver->support(Contact::class));
        self::assertSame('Contact', $resolver->resolve(Contact::class));
    }

    public function testPatternIsReplacedWithValue(): void
    {
        $resolver = new PatternComponentNameResolver([
            '~^Davajlama\\\\Schemator\\\\Demo\\\\BookStore\\\\Schema~' => 'Api',
        ]);

        self::assertSame('ApiContact', $resolver->resolve(Contact::class));
    }

    public function testRegularExpressionFeaturesAreSupported(): void
    {
        $resolver = new PatternComponentNameResolver([
            '~^.+\\\\~' => '',
        ]);

        self::assertSame('Contact', $resolver->resolve(Contact::class));
    }

    public function testMultiplePatternsAreApplied(): void
    {
        $resolver = new PatternComponentNameResolver([
            '~^Davajlama\\\\Schemator\\\\Demo\\\\~' => '',
            '~bookstore~i' => 'Store',
        ]);

        self::assertSame('StoreSchemaContact', $resolver->resolve(Contact::class));
    }

    public function testNameWithoutMatchFallsBackToDefaultBehaviour(): void
    {
        $resolver = new PatternComponentNameResolver([
            '~^App\\\\Unknown\\\\~' => '',
        ]);

        self::assertSame('DavajlamaSchematorDemoBookStoreSchemaContact', $resolver->resolve(Contact::class));
    }

    public function testSchemaNameIsUsedWhenAvailable(): void
    {
        $schema = new Schema('contactList');

        $resolver = new PatternComponentNameResolver([
            '~List$~' => '',
        ]);

        self::assertSame('Contact', $resolver->resolve($schema));
    }
}
