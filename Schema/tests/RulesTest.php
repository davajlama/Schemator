<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Tests;

use Davajlama\Schemator\Schema\Exception\ValidationFailedException;
use Davajlama\Schemator\Schema\Extractor\ArrayExtractor;
use Davajlama\Schemator\Schema\Extractor\ExtractorAwareInterface;
use Davajlama\Schemator\Schema\RuleInterface;
use Davajlama\Schemator\Schema\Rules\ArrayOfValues;
use Davajlama\Schemator\Schema\Rules\DateTime;
use Davajlama\Schemator\Schema\Rules\Email;
use Davajlama\Schemator\Schema\Rules\Enum;
use Davajlama\Schemator\Schema\Rules\Length;
use Davajlama\Schemator\Schema\Rules\Max;
use Davajlama\Schemator\Schema\Rules\MaxItems;
use Davajlama\Schemator\Schema\Rules\MaxLength;
use Davajlama\Schemator\Schema\Rules\Min;
use Davajlama\Schemator\Schema\Rules\MinItems;
use Davajlama\Schemator\Schema\Rules\MinLength;
use Davajlama\Schemator\Schema\Rules\Range;
use Davajlama\Schemator\Schema\Rules\Type\ArrayOfIntegerType;
use Davajlama\Schemator\Schema\Rules\Type\ArrayOfStringType;
use Davajlama\Schemator\Schema\Rules\Type\ArrayType;
use Davajlama\Schemator\Schema\Rules\Type\BoolType;
use Davajlama\Schemator\Schema\Rules\Type\FloatType;
use Davajlama\Schemator\Schema\Rules\Type\IntegerType;
use Davajlama\Schemator\Schema\Rules\Type\StringType;
use Davajlama\Schemator\Schema\Rules\Unique;
use Davajlama\Schemator\Schema\Rules\Url;
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
            'arrayOfString' => [new ArrayOfStringType(), [[], ['132', 'test', '']]],
            'arrayOfInteger' => [new ArrayOfIntegerType(), [[], [1, 2, 3], [0]]],
            'arrayOfValues' => [new ArrayOfValues(['CZ', 'EN', 'GB']), [[], ['CZ'], ['CZ', 'CZ'], ['CZ', 'EN', 'GB']]],
            'boolean' => [new BoolType(), [true, false]],
            'enum' => [new Enum(['CZ', 'EN', 'GB']), ['EN', 'CZ']],
            'min' => [new Min(10), [11, 10.5]],
            'max' => [new Max(10), [9, -1, 0, 1.1]],
            'range' => [new Range(10, 20), [10, 20, 15]],
            'minLength' => [new MinLength(3), ['abc', 'abcd']],
            'maxLength' => [new MaxLength(5), ['', 'abc']],
            'length' => [new Length(2), ['CS', 'EN']],
            'email' => [new Email(), ['foo@bar.cz', 'foo+100@bar.com']],
            'dateTime' => [new DateTime('Y-m-d H:i:s'), ['2022-06-06 12:30:30']],
            'maxItems' => [new MaxItems(3), [[], [1, 2, 3]]],
            'minItems' => [new MinItems(1), [[1]]],
            'unique' => [new Unique(), [[], [1, 2]]],
            'url' => [new Url(), ['https://example.com', 'https://example.com/foobar']],
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
            'arrayOfString' => [new ArrayOfStringType(), 'Array contain one or more non-string values.', [[1], [null]]],
            'arrayOfInteger' => [new ArrayOfIntegerType(), 'Array contain one or more non-integer values.', [[null], ['1'], [1, false]]],
            'arrayOfValues' => [new ArrayOfValues(['CZ', 'EN', 'GB']), 'Array contain one or more non-predefined values.', [[null], [1], ['IT']]],
            'boolean' => [new BoolType(), 'Must be a boolean.', [1, 0, null]],
            'enum' => [new Enum(['CZ', 'EN']), 'Must be one of [CZ|EN].', ['DE', 'PL', null, 1]],
            'min' => [new Min(10), 'Must be greater than 10.', [9, 9.9, 0]],
            'max' => [new Max(10), 'Must be lower than 10.', [10.1, 11, 100]],
            'range' => [new Range(10, 20), 'Must be between 10 - 20.', [5, 25]],
            'minLength' => [new MinLength(3), 'Must be min 3 chars length.', ['ab', '']],
            'maxLength' => [new MaxLength(5), 'Must be max 5 chars length.', ['123456', 'ščř']],
            'length' => [new Length(2), 'Must be 2 chars length.', ['CES']],
            'email' => [new Email(), 'Invalid e-mail format.', ['foo', 'foo.cz', 'foo@bar@bar.com']],
            'dateTime' => [new DateTime('Y-m-d H:i:s'), 'Invalid datetime format Y-m-d H:i:s.', ['2022-06-06']],
            'maxItems' => [new MaxItems(3), 'Maximum items of an array is 3.', [[1, 2, 3, 4]]],
            'minItems' => [new MinItems(1), 'Minimum items of an array is 1.', [[]]],
            'unique' => [new Unique(), 'Array contain non-unique values.', [[1, 1]]],
            'url' => [new Url(), 'Invalid url format.', ['https://', '/foobar']],
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
