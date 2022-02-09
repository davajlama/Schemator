<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Tests\Rules;

use Davajlama\Schemator\Extractor\ArrayExtractor;
use Davajlama\Schemator\Rules\StringTypeRule;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class StringTypeRuleTest extends TestCase
{
    public function testSuccessValidation(): void
    {
        $extractor = new ArrayExtractor();
        $rule = new StringTypeRule();
        $rule->setExtractor($extractor);

        $data = ['value' => ''];
        $rule->validate($data, 'value');

        $data = ['value' => '0'];
        $rule->validate($data, 'value');

        $data = ['value' => 'stringValue'];
        $rule->validate($data, 'value');
    }

    public function testFailedValidation(): void
    {
        $extractor = new ArrayExtractor();
        $rule = new StringTypeRule();
        $rule->setExtractor($extractor);

        self::expectException(InvalidArgumentException::class);

        $data = ['value' => 123];
        $rule->validate($data, 'value');
    }
}
