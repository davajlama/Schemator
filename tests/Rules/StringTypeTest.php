<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Tests\Rules;

use Davajlama\Schemator\Extractor\ArrayValueExtractor;
use Davajlama\Schemator\Rules\CallbackRule;
use Davajlama\Schemator\Rules\StringTypeRule;
use PHPUnit\Framework\TestCase;

final class StringTypeTest extends TestCase
{
    public function testSuccessValidation(): void
    {
        $extractor = new ArrayValueExtractor();
        $rule = new StringTypeRule();
        $rule->setExtractor($extractor);

        $data = ['value' => ''];
        self::assertNull($rule->validate($data, 'value'));

        $data = ['value' => '0'];
        self::assertNull($rule->validate($data, 'value'));

        $data = ['value' => 'stringValue'];
        self::assertNull($rule->validate($data, 'value'));
    }

    public function testFailedValidation(): void
    {
        $extractor = new ArrayValueExtractor();
        $rule = new StringTypeRule();
        $rule->setExtractor($extractor);

        self::expectException(\InvalidArgumentException::class);

        $data = ['value' => 123];
        $rule->validate($data, 'value');
    }
}