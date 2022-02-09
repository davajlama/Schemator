<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Tests\Rules;

use Davajlama\Schemator\Exception\ValidationFailedException;
use Davajlama\Schemator\Extractor\ArrayExtractor;
use Davajlama\Schemator\Rules\OneOf;
use PHPUnit\Framework\TestCase;

final class OneOfTest extends TestCase
{
    public function testSuccess(): void
    {
        $extractor = new ArrayExtractor();
        $rule = new OneOf(['FOO', 'BAR']);
        $rule->setExtractor($extractor);

        $data = ['value' => 'FOO'];
        self::assertNull($rule->validate($data, 'value'));

        $data = ['value' => 'BAR'];
        self::assertNull($rule->validate($data, 'value'));
    }

    public function testFailed(): void
    {
        $extractor = new ArrayExtractor();
        $rule = new OneOf(['FOO', 'BAR']);
        $rule->setExtractor($extractor);

        self::expectException(ValidationFailedException::class);

        $data = ['value' => 'FOOBAR'];
        $rule->validate($data, 'value');
    }
}
