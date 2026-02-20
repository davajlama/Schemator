<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Demo\BookStore\Manage\Request;

use Davajlama\Schemator\Schema\Value;
use Davajlama\Schemator\SchemaAttributes\Attribute\RequiredAll;

#[RequiredAll]
final class AuthorUpdate
{
    public function __construct(
        public Value|string $firstname = Value::NONE,
        public Value|string $surname = Value::NONE,
    ) {
    }
}
