<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Demo\BookStore\Manage\Request;

use Davajlama\Schemator\SchemaAttributes\Attribute\MaxLength;
use Davajlama\Schemator\SchemaAttributes\Attribute\MinLength;
use Davajlama\Schemator\SchemaAttributes\Attribute\RequiredAll;
use Davajlama\Schemator\SchemaAttributes\Attribute\Url;

#[RequiredAll]
final class ProductCreate
{
    public function __construct(
        #[MinLength(3)] #[MaxLength(255)] public string $name,
        #[Url] public string $url,
    ) {
    }
}
