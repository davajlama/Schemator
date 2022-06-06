<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Tests;

use Davajlama\Schemator\Exception\ValidationFailedException;
use Davajlama\Schemator\Extractor\ArrayExtractor;
use Davajlama\Schemator\Extractor\ExtractorAwareInterface;
use Davajlama\Schemator\RuleInterface;
use Davajlama\Schemator\Rules\IntegerType;
use Davajlama\Schemator\Rules\StringType;
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
        ];
    }

    /**
     * @param mixed[] $data
     * @dataProvider invalidDataRulesProvider
     */
    public function testInvalidSimpleRules(RuleInterface&ExtractorAwareInterface $rule, string $message, array $data): void
    {
        $arrayExtractor = new ArrayExtractor();
        $rule->setExtractor($arrayExtractor);

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
