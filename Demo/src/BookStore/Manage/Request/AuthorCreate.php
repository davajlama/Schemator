<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Demo\BookStore\Manage\Request;

use Davajlama\Schemator\SchemaAttributes\Attribute\Example;
use Davajlama\Schemator\SchemaAttributes\Attribute\RequiredAll;

#[RequiredAll]
final class AuthorCreate
{
    public function __construct(
        #[Example('Dave')] public string $firstname,
        #[Example('Lister')] public string $surname,
    ) {
    }
}
