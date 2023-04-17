<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Demo\Entity;

use Davajlama\Schemator\Schema\Schema;

final class Integration
{
    public static function createSchema(): Schema
    {
        $schema = new Schema(self::class);
        $schema->prop('name')->string()->required();
        $schema->prop('description')->string()->required();
        $schema->prop('integers')->arrayOfInteger();
        $schema->prop('context')->dynamicObject();

        return $schema;
    }
}
