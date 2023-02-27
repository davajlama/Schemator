<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Demo\Entity;

use Davajlama\Schemator\Demo\Schema\Response\Author;
use Davajlama\Schemator\SchemaAttributes\Attribute\AdditionalProperties;
use Davajlama\Schemator\SchemaAttributes\Attribute\MaxLength;

#[AdditionalProperties(true)]
final class Product
{
    public function __construct(
        public int $id,
        #[MaxLength(255)] public string $name,
        #[MaxLength(1024)] public ?string $description,
        public float|int $price,
        public Author $author,
        public string|int|null $image,
    ) {
    }
}
