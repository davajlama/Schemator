<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SanitizerAttributes\Tests\Fixtures;

use Davajlama\Schemator\SanitizerAttributes\Attribute\DefaultValue;
use Davajlama\Schemator\SanitizerAttributes\Attribute\Trim;

#[Trim]
final class Author
{
    /**
     * @param Book[] $books
     */
    public function __construct(
        public string $firstname,
        public string $lastname,
        public int|float|null $age,
        public array $books,
        #[DefaultValue('CZ')] public string $country,
    ) {
    }
}
