<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Tests;

use Davajlama\Schemator\Exception\ValidationFailedException;
use Davajlama\Schemator\Extractor\ArrayExtractor;
use Davajlama\Schemator\Extractor\ExtractorAwareInterface;
use Davajlama\Schemator\RuleInterface;
use Davajlama\Schemator\Rules\Email;
use Davajlama\Schemator\Rules\Enum;
use Davajlama\Schemator\Rules\Length;
use Davajlama\Schemator\Rules\Max;
use Davajlama\Schemator\Rules\MaxLength;
use Davajlama\Schemator\Rules\Min;
use Davajlama\Schemator\Rules\MinLength;
use Davajlama\Schemator\Rules\Range;
use Davajlama\Schemator\Rules\Type\ArrayType;
use Davajlama\Schemator\Rules\Type\BoolType;
use Davajlama\Schemator\Rules\Type\FloatType;
use Davajlama\Schemator\Rules\Type\IntegerType;
use Davajlama\Schemator\Rules\Type\StringType;
use PHPUnit\Framework\TestCase;

final class RulesTest extends TestCase
{
    /**
     * @param mixed[] $data
     * @dataProvider validDataRulesProvider
     */
    public function testValidSimpleRules(RuleInterface $rule, array $data): void
    {
        $arrayExtractor = new ArrayExtractor();

        if ($rule instanceof ExtractorAwareInterface) {
            $rule->setExtractor($arrayExtractor);
        }

        foreach ($data as $value) {
            $payload = ['property' => $value];
            $result = $this->validate($rule, $payload, 'property');
            self::assertNull($result);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function validDataRulesProvider(): array
    {
        return [
            'string' => [new StringType(), ['123', 'test', '']],
            'integer' => [new IntegerType(), [123, 0]],
            'float' => [new FloatType(), [1, 1.0]],
            'array' => [new ArrayType(), [[], ['test']]],
            'boolean' => [new BoolType(), [true, false]],
            'enum' => [new Enum(['CZ', 'EN', 'GB']), ['EN', 'CZ']],
            'min' => [new Min(10), [11, 10.5]],
            'max' => [new Max(10), [9, -1, 0, 1.1]],
            'range' => [new Range(10, 20), [10, 20, 15]],
            'minLength' => [new MinLength(3), ['abc', 'abcd']],
            'maxLength' => [new MaxLength(5), ['', 'abc']],
            'length' => [new Length(2), ['CS', 'EN']],
            'email' => [new Email(), ['foo@bar.cz', 'foo+100@bar.com']],
        ];
    }

    /**
     * @param mixed[] $data
     * @dataProvider invalidDataRulesProvider
     */
    public function testInvalidSimpleRules(RuleInterface $rule, string $message, array $data): void
    {
        $arrayExtractor = new ArrayExtractor();

        if ($rule instanceof ExtractorAwareInterface) {
            $rule->setExtractor($arrayExtractor);
        }

        foreach ($data as $value) {
            $payload = ['property' => $value];
            $result = $this->validate($rule, $payload, 'property');
            self::assertInstanceOf(ValidationFailedException::class, $result);
            self::assertSame($message, $result->getMessage());
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function invalidDataRulesProvider(): array
    {
        return [
            'string' => [new StringType(), 'Must be a string.', [null, 123, false]],
            'integer' => [new IntegerType(), 'Must be an integer.', [false, 0.0, '0']],
            'float' => [new FloatType(), 'Must be a float.', [false, '1.0']],
            'array' => [new ArrayType(), 'Must be an array.', [1, true, '[]']],
            'boolean' => [new BoolType(), 'Must be a boolean.', [1, 0, null]],
            'enum' => [new Enum(['CZ', 'EN']), 'Must be one of [CZ, EN]', ['DE', 'PL', null, 1]],
            'min' => [new Min(10), 'Must be greather than 10', [9, 9.9, 0]],
            'max' => [new Max(10), 'Must be lower than 10', [10.1, 11, 100]],
            'range' => [new Range(10, 20), 'Must be between 10 - 20.', [5, 25]],
            'minLength' => [new MinLength(3), 'Must be min 3 chars length.', ['ab', '']],
            'maxLength' => [new MaxLength(5), 'Must be max 5 chars length.', ['123456', 'ščř']],
            'length' => [new Length(2), 'Must be 2 chars length.', ['CES']],
            'email' => [new Email(), 'Wrong e-mail format.', ['foo', 'foo.cz', 'foo@bar@bar.com']],
        ];
    }

    /**
     * @param mixed[] $payload
     */
    protected function validate(RuleInterface $rule, array $payload, string $property): ?ValidationFailedException
    {
        try {
            $rule->validate($payload, $property);
            return null;
        } catch (ValidationFailedException $e) {
            return $e;
        }
    }
}
