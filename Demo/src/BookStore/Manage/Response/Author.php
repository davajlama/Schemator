<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Demo\BookStore\Manage\Response;

use Davajlama\Schemator\SchemaAttributes\Attribute\DateTime;
use Davajlama\Schemator\SchemaAttributes\Attribute\Enum;
use Davajlama\Schemator\SchemaAttributes\Attribute\RangeLength;
use Davajlama\Schemator\SchemaAttributes\Attribute\RequiredAll;

#[RequiredAll]
final class Author
{
    public function __construct(
        public int $id,
        #[RangeLength(1, 30)] public string $firstname,
        #[RangeLength(1, 30)] public string $surname,
        public ?Contact $contact,
        #[DateTime('Y-m-d H:i:s')] public string $birthday,
        #[Enum(['MALE', 'FEMALE'])] public string $sex,
    ) {
    }
}
