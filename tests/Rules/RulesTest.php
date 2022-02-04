<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Tests\Rules;

use Davajlama\Schemator\Exception\ValidationFailedException;
use Davajlama\Schemator\Extractor\ArrayExtractor;
use Davajlama\Schemator\Rules\BaseRule;
use Davajlama\Schemator\Rules\BooleanType;
use Davajlama\Schemator\Rules\IntegerType;
use Davajlama\Schemator\Rules\NonEmptyStringRule;
use Davajlama\Schemator\Rules\NullableInteger;
use Davajlama\Schemator\Rules\NullableString;
use Davajlama\Schemator\Rules\StringTypeRule;
use Exception;
use PHPUnit\Framework\TestCase;

final class RulesTest extends TestCase
{
    /**
     * @dataProvider rulesProvider
     *
     * @param mixed[] $validValues
     * @param mixed[] $invalidValues
     */
    public function testValidation(BaseRule $rule, $validValues, $invalidValues): void
    {
        $rule->setExtractor(new ArrayExtractor());

        foreach($validValues as $validValue) {
            $data = ['value' => $validValue];
            $rule->validate($data, 'value');
        }

        foreach($invalidValues as $invalidValue) {
            try {
                $thrown = null;
                $data = ['value' => $invalidValue];
                $rule->validate($data, 'value');
            } catch (Exception $e) {
                $thrown = $e;
            }

            self::assertInstanceOf(ValidationFailedException::class, $thrown);
        }
    }

    public function rulesProvider(): array
    {
        return [
            'string type' => [new StringTypeRule(), ['text'], [9999]],
            'integer type' => [new IntegerType(), [9999], ['text']],
            'boolean type' => [new BooleanType(), [true], ['true']],
            'non empty string' => [new NonEmptyStringRule(), ['text'], ['', 0, false]],
            'nullable string' => [new NullableString(), ['', 'text', null], [0, false]],
            'nullable integer' => [new NullableInteger(), [1, null], ['1', false, true]]
        ];
    }
}