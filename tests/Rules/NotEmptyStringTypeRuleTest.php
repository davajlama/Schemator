<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Tests\Rules;

use Davajlama\Schemator\Extractor\ArrayValueExtractor;
use Davajlama\Schemator\Rules\NotEmptyStringTypeRule;
use PHPUnit\Framework\TestCase;

final class NotEmptyStringTypeRuleTest extends TestCase
{
    public function testSuccessValidation(): void
    {
        $extractor = new ArrayValueExtractor();
        $rule = new NotEmptyStringTypeRule();
        $rule->setExtractor($extractor);

        $data = ['value' => '0'];
        self::assertNull($rule->validate($data, 'value'));

        $data = ['value' => 'stringValue'];
        self::assertNull($rule->validate($data, 'value'));
    }

    public function testFailedEmptyValidation(): void
    {
        $extractor = new ArrayValueExtractor();
        $rule = new NotEmptyStringTypeRule();
        $rule->setExtractor($extractor);

        self::expectException(\InvalidArgumentException::class);

        $data = ['value' => ''];
        $rule->validate($data, 'value');
    }

    public function testFailedTypeValidation(): void
    {
        $extractor = new ArrayValueExtractor();
        $rule = new NotEmptyStringTypeRule();
        $rule->setExtractor($extractor);

        self::expectException(\InvalidArgumentException::class);

        $data = ['value' => 999];
        $rule->validate($data, 'value');
    }

    public function testFailedWhiteSpaceValidation(): void
    {
        $extractor = new ArrayValueExtractor();
        $rule = new NotEmptyStringTypeRule();
        $rule->setExtractor($extractor);

        self::expectException(\InvalidArgumentException::class);

        $data = ['value' => '    '];
        $rule->validate($data, 'value');
    }
}