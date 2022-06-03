<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Tests;

use Davajlama\Schemator\ArrayValidator;
use Davajlama\Schemator\Exception\ValidationFailedException;
use Davajlama\Schemator\MessagesFormatter;
use Davajlama\Schemator\Schema;
use PHPUnit\Framework\TestCase;

use function var_dump;

final class ValidatorTest extends TestCase
{
    public function testValidPayload(): void
    {
        $schema = new Schema();
        $schema->additionalProperties(false);
        $schema->prop('firstname')->string();
        $schema->prop('lastname')->string();

        $payload = [
            'firstname' => 'Dave',
            'lastname' => 'Lister',
        ];

        self::assertTrue($this->validate($schema, $payload));
    }

    public function testInvalidPayload(): void
    {
        $schema = new Schema();
        $schema->additionalProperties(false);
        $schema->prop('firstname')->string();
        $schema->prop('lastname')->string();

        $payload = [
            'firstname' => 123,
            'lastname' => 123,
            'age' => 123,
        ];

        $exception = $this->validate($schema, $payload);

        self::assertInstanceOf(ValidationFailedException::class, $exception);
        var_dump(MessagesFormatter::formatErrors($exception->getErrors()));
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
