<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaConditions\Tests;

use Davajlama\Schemator\Schema\Exception\ValidationFailedException;
use Davajlama\Schemator\Schema\Extractor\ArrayExtractor;
use Davajlama\Schemator\Schema\Extractor\ExtractorAwareInterface;
use Davajlama\Schemator\SchemaConditions\ConditionInterface;
use Davajlama\Schemator\SchemaConditions\Conditions\FilledAllOrNone;
use Davajlama\Schemator\SchemaConditions\Conditions\FilledIfAllNotExists;
use Davajlama\Schemator\SchemaConditions\Conditions\FilledIfAllNull;
use Davajlama\Schemator\SchemaConditions\Conditions\FilledIfOneNotExists;
use Davajlama\Schemator\SchemaConditions\Conditions\FilledIfOneNull;
use Davajlama\Schemator\SchemaConditions\Conditions\RequiredIfAllNotExists;
use Davajlama\Schemator\SchemaConditions\Conditions\RequiredIfAllNull;
use Davajlama\Schemator\SchemaConditions\Conditions\RequiredIfOneNotExists;
use Davajlama\Schemator\SchemaConditions\Conditions\RequiredIfOneNull;
use PHPUnit\Framework\TestCase;

final class ConditionsTest extends TestCase
{
    public function testSuccessValidation(): void
    {
        $payload = [
            'company' => 'Red Dwarf s.r.o.',
            'vatId' => '123456789',
            'firstname' => 'Dave',
            'surname' => 'Lister',
            'firstname2' => null,
            'surname2' => null,
        ];

        self::assertNull($this->validateCondition(new FilledAllOrNone(['company', 'vatId']), $payload));
        self::assertNull($this->validateCondition(new FilledIfAllNotExists(['company', 'vatId'], ['company2', 'vatId2']), $payload));
        self::assertNull($this->validateCondition(new FilledIfOneNotExists(['company', 'vatId'], ['firstname', 'vatId2']), $payload));
        self::assertNull($this->validateCondition(new FilledIfAllNull(['firstname', 'surname'], ['firstname2', 'surname1']), $payload));
        self::assertNull($this->validateCondition(new FilledIfOneNull(['firstname'], ['surname', 'surname2']), $payload));
        self::assertNull($this->validateCondition(new RequiredIfAllNotExists(['company', 'vatId'], ['company2', 'vatId2']), $payload));
        self::assertNull($this->validateCondition(new RequiredIfOneNotExists(['company', 'vatId'], ['firstname', 'vatId2']), $payload));
        self::assertNull($this->validateCondition(new RequiredIfAllNull(['firstname', 'surname'], ['firstname2', 'surname1']), $payload));
        self::assertNull($this->validateCondition(new RequiredIfOneNull(['firstname'], ['surname', 'surname2']), $payload));
    }

    public function testFilledAllOrNoneCondition(): void
    {
        $payload = [
            'company' => 'Red Dwarf s.r.o.',
            'vatId' => '123456789',
        ];

        self::assertNull($this->validateCondition(new FilledAllOrNone(['company', 'vatId']), $payload));

        $payload = [
            'company' => 'Red Dwarf s.r.o.',
        ];

        $exception = $this->validateCondition(new FilledAllOrNone(['company', 'vatId']), $payload);
        self::assertNotNull($exception);
        self::assertCount(1, $exception->getErrors());
        self::assertSame('vatId', $exception->getErrors()[0]->getProperty());
        self::assertSame('Property is required.', $exception->getErrors()[0]->getMessage());

        $payload = [
            'company' => 'Red Dwarf s.r.o.',
            'vatId' => null,
        ];

        $exception = $this->validateCondition(new FilledAllOrNone(['company', 'vatId']), $payload);
        self::assertNotNull($exception);
        self::assertCount(1, $exception->getErrors());
        self::assertSame('vatId', $exception->getErrors()[0]->getProperty());
        self::assertSame('Property cannot be null.', $exception->getErrors()[0]->getMessage());
    }

    /**
     * @param mixed[] $payload
     */
    private function validateCondition(ConditionInterface $condition, array $payload): ?ValidationFailedException
    {
        if ($condition instanceof ExtractorAwareInterface) {
            $condition->setExtractor(new ArrayExtractor());
        }

        try {
            $condition->validate($payload);
        } catch (ValidationFailedException $e) {
            return $e;
        }

        return null;
    }
}
