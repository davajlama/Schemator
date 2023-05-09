<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaConditions\Tests;

use Davajlama\Schemator\Schema\Exception\ValidationFailedException;
use Davajlama\Schemator\SchemaConditions\ArraySchemaConditions;
use PHPUnit\Framework\TestCase;

final class ArraySchemaConditionsTest extends TestCase
{
    public function testBaseConditions(): void
    {
        $payload = [
            'firstname' => null,
            'surname' => null,
        ];

        self::expectException(ValidationFailedException::class);

        $conditions = new ArraySchemaConditions();
        $conditions->props('id')->requiredIfAllNull('firstname', 'surname');
        $conditions->validate($payload);
    }
}
