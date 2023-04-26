<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Demo\BookStore\Manage\Response;

use Davajlama\Schemator\SchemaAttributes\Attribute\DateTime;
use Davajlama\Schemator\SchemaAttributes\Attribute\DynamicObject;
use Davajlama\Schemator\SchemaAttributes\Attribute\RangeLength;
use Davajlama\Schemator\SchemaAttributes\Attribute\RequiredAll;

#[RequiredAll]
final class Attribute
{
    /**
     * @param array<string, mixed> $params
     */
    public function __construct(
        public int $id,
        #[RangeLength(1, 10)] public string $name,
        #[DynamicObject] public array $params,
        #[DateTime('Y-m-d H:i:s')] public string $created,
    ) {
    }
}
