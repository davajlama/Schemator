<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Demo\BookStore\Manage\Response;

use Davajlama\Schemator\SchemaAttributes\Attribute\DateTime;
use Davajlama\Schemator\SchemaAttributes\Attribute\Enum;
use Davajlama\Schemator\SchemaAttributes\Attribute\Example;
use Davajlama\Schemator\SchemaAttributes\Attribute\RangeLength;
use Davajlama\Schemator\SchemaAttributes\Attribute\RequiredAll;

#[RequiredAll]
final class Author
{
    private int $id;

    private string $firstname;

    private string $surname;

    private ?Contact $contact;

    private string $birthday;

    private string $sex;

    public function __construct(
        int $id,
        #[RangeLength(1, 30)] #[Example('Dave')] string $firstname,
        #[RangeLength(1, 30)] #[Example('Lister')] string $surname,
        ?Contact $contact,
        #[DateTime('Y-m-d H:i:s')] string $birthday,
        #[Enum(['MALE', 'FEMALE'])] string $sex,
    ) {
        $this->id = $id;
        $this->firstname = $firstname;
        $this->surname = $surname;
        $this->contact = $contact;
        $this->birthday = $birthday;
        $this->sex = $sex;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function getBirthday(): string
    {
        return $this->birthday;
    }

    public function getSex(): string
    {
        return $this->sex;
    }
}
