<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Demo\BookStore\Manage\Request;

use Davajlama\Schemator\SchemaAttributes\Attribute\RequiredAll;

#[RequiredAll]
final class CreateAttribute
{
    public function __construct(
        public string $name,
    ) {
    }
}
