<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SanitizerAttributes\Tests\Fixtures;

use Davajlama\Schemator\SanitizerAttributes\Attribute\Trim;

#[Trim]
final class Author
{
    public function __construct(
        public string $firstname,
        public string $lastname,
        public int $age,
    ) {
    }
}
