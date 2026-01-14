<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SanitizerAttributes\Tests\Fixtures;

use Davajlama\Schemator\SanitizerAttributes\Attribute\Trim;
use Davajlama\Schemator\Schema\Value;

final class Book
{
    public function __construct(
        #[Trim] public string $name,
        public ?Author $author,
        public Author|null $secondAuthor,
        public int $pages,
        public Value|int|null $authorId = Value::NONE,
        public Value|Author $thirdAuthor = Value::NONE,
        public Value|Author|null $fourthAuthor = Value::NONE,
    ) {
    }
}
