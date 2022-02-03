<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Tests\Rules;

use Davajlama\Schemator\Exception\ValidationFailedException;
use Davajlama\Schemator\Extractor\ArrayExtractor;
use Davajlama\Schemator\Rules\CallbackRule;
use PHPUnit\Framework\TestCase;

final class CallbackRuleTest extends TestCase
{
    public function testSuccessValidation(): void
    {
        $extractor = new ArrayExtractor();
        $rule = new CallbackRule(fn($value) => $value !== 'deniedValue');
        $rule->setExtractor($extractor);

        $data = ['value' => 'allowedValue'];
        self::assertNull($rule->validate($data, 'value'));
    }

    public function testFailedValidation(): void
    {
        $extractor = new ArrayExtractor();
        $rule = new CallbackRule(fn($value) => $value !== 'deniedValue');
        $rule->setExtractor($extractor);

        self::expectException(ValidationFailedException::class);

        $data = ['value' => 'deniedValue'];
        $rule->validate($data, 'value');
    }
}