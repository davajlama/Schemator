<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Demo\BookStore\Manage\Response;

use Davajlama\Schemator\SchemaAttributes\Attribute\ArrayOf;
use Davajlama\Schemator\SchemaAttributes\Attribute\Enum;
use Davajlama\Schemator\SchemaAttributes\Attribute\Range;
use Davajlama\Schemator\SchemaAttributes\Attribute\RangeLength;
use Davajlama\Schemator\SchemaAttributes\Attribute\RequiredAll;
use Davajlama\Schemator\SchemaAttributes\Attribute\Unique;

#[RequiredAll]
final class Product
{
    /**
     * @param Author[] $authors
     * @param Attribute[] $attributes
     * @param Image[] $images
     */
    public function __construct(
        public int $id,
        #[RangeLength(3, 255)] public string $name,
        public ?string $description,
        public float $price,
        public int $store,
        #[ArrayOf(Author::class)] #[Unique] public array $authors,
        #[ArrayOf(Attribute::class)] #[Unique] public array $attributes,
        #[ArrayOf(Image::class)] public array $images,
        #[Enum(['BOOK', 'MAGAZINE'])] public string $type,
        #[range(0, 100)] public int $rating,
    ) {
    }
}
