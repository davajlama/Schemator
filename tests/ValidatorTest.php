<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Tests;

use Davajlama\Schemator\ArrayValidator;
use Davajlama\Schemator\Exception\ValidationFailedException;
use Davajlama\Schemator\Extractor\ExtractorInterface;
use Davajlama\Schemator\MessagesFormatter;
use Davajlama\Schemator\Schema;
use PHPUnit\Framework\TestCase;

use function is_integer;
use function var_dump;

final class ValidatorTest extends TestCase
{
    public function testValidPayload(): void
    {
        $schema = new Schema();
        $schema->additionalProperties(false);
        $schema->prop('firstname')->string();
        $schema->prop('lastname')->string();
        $schema->prop('age')->callback(static function (mixed $paylod, string $property, ExtractorInterface $extractor): void {
            $value = $extractor->extract($paylod, $property);
            if (!is_integer($value)) {
                throw new ValidationFailedException('Must be an integer.');
            }
        });

        $payload = [
            'firstname' => 'Dave',
            'lastname' => 'Lister',
            'age' => 25,
        ];

        self::assertTrue($this->validate($schema, $payload));
    }

    public function testInvalidPayload(): void
    {
        $schema = new Schema();
        $schema->additionalProperties(false);
        $schema->prop('firstname')->string();
        $schema->prop('lastname')->string();
        $schema->prop('age')->callback(static function (mixed $payload, string $property, ExtractorInterface $extractor): void {
            $value = $extractor->extract($payload, $property);
            if (!is_integer($value)) {
                throw new ValidationFailedException('Must be an integer.');
            }
        });

        $payload = [
            'unknownProperty' => false,
            'firstname' => 123,
            'lastname' => 123,
            'age' => '123',
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
