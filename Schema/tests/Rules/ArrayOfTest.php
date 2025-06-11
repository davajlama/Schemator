<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Tests\Rules;

use Davajlama\Schemator\Schema\Exception\ValidationFailedException;
use Davajlama\Schemator\Schema\Schema;
use Davajlama\Schemator\Schema\Validator\ArrayValidator;
use PHPUnit\Framework\TestCase;

final class ArrayOfTest extends TestCase
{
    public function testValidInput(): void
    {
        $payloadA = [
            'product' => [
                'type' => 'A',
                'name' => 'Test 01',
            ],
        ];

        $payloadB = [
            'product' => [
                'type' => 'B',
                'title' => 'Test 02',
            ],
        ];

        $typeA = new Schema();
        $typeA->prop('type')->string();
        $typeA->prop('name')->string();

        $typeB = new Schema();
        $typeB->prop('type')->string();
        $typeB->prop('title')->string();

        $schema = new Schema();
        $schema->prop('product')->anyOf('type', ['A' => $typeA, 'B' => $typeB]);

        self::assertTrue($this->validate($schema, $payloadA));
        self::assertTrue($this->validate($schema, $payloadB));
    }

    public function testInvalidInput(): void
    {
        $payload = [
            'product' => [
                'type' => 'A',
                'name' => 123,
            ],
        ];

        $type = new Schema();
        $type->prop('type')->string();
        $type->prop('name')->string();

        $schema = new Schema();
        $schema->prop('product')->anyOf('type', ['A' => $type]);

        $exception = $this->validate($schema, $payload);

        self::assertInstanceOf(ValidationFailedException::class, $exception);
        self::assertCount(1, $exception->getErrors());
        self::assertSame('product', $exception->getErrors()[0]->getProperty());
        self::assertSame('Data is not valid.', $exception->getErrors()[0]->getMessage()->toString());
        self::assertCount(1, $exception->getErrors()[0]->getErrors());
        self::assertSame('Must be a string.', $exception->getErrors()[0]->getErrors()[0]->getMessage()->toString());
    }

    /**
     * @param mixed[] $payload
     */
    private function validate(Schema $schema, array $payload): bool|ValidationFailedException
    {
        try {
            $validator = new ArrayValidator();
            $validator->validate($schema, $payload);

            return true;
        } catch (ValidationFailedException $e) {
            return $e;
        }
    }
}
