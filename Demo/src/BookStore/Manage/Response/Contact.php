<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Demo\BookStore\Manage\Response;

use Davajlama\Schemator\SchemaAttributes\Attribute\Email;
use Davajlama\Schemator\SchemaAttributes\Attribute\RequiredAll;

#[RequiredAll]
final class Contact
{
    public function __construct(
        #[Email] public string $email,
        public string $phone,
    ) {
    }
}
