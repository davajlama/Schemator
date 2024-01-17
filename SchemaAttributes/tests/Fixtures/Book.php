<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaAttributes\Tests\Fixtures;

use Davajlama\Schemator\SanitizerAttributes\Attribute\Trim;

final class Book
{
    public function __construct(
        #[Trim] public string $name,
        public int $pages,
    ) {
    }
}
