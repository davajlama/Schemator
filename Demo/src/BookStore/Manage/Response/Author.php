<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Demo\BookStore\Manage\Response;

use Davajlama\Schemator\SchemaAttributes\Attribute\RequiredAll;

#[RequiredAll]
final class Author
{
    public function __construct(
        public int $id,
        public ?Contact $contact,
    ) {
    }
}
