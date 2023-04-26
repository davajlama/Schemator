<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Demo\BookStore\Manage\Response;

use Davajlama\Schemator\SchemaAttributes\Attribute\RequiredAll;

#[RequiredAll]
final class Product
{
    public function __construct(
        public int $id,
    ) {
    }
}
