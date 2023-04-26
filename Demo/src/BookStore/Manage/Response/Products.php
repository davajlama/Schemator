<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Demo\BookStore\Manage\Response;

use Davajlama\Schemator\SchemaAttributes\Attribute\ArrayOf;
use Davajlama\Schemator\SchemaAttributes\Attribute\RequiredAll;

#[RequiredAll]
final class Products
{
    /**
     * @param Product[] $list
     */
    public function __construct(
        #[ArrayOf(Product::class)] public array $list,
    ) {
    }
}
