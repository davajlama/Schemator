<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Demo\BookStore\Manage\Response;

use Davajlama\Schemator\SchemaAttributes\Attribute\Email;
use Davajlama\Schemator\SchemaAttributes\Attribute\PropertyInformation;
use Davajlama\Schemator\SchemaAttributes\Attribute\RequiredAll;

#[PropertyInformation(name: 'phone', example: '+420123456789')]
#[PropertyInformation(name: 'email', example: 'dave@lister.com')]

#[RequiredAll]
final class Contact
{
    public function __construct(
        #[Email] public string $email,
        public string $phone,
    ) {
    }
}
