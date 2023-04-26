<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Demo\BookStore\Schema;

use Davajlama\Schemator\Schema\Schema;

final class Author extends Schema
{
    public function __construct()
    {
        parent::__construct();

        $this->requiredDefaultValue(true);
        $this->prop('id')->integer();
        $this->prop('firstname')->string()->minLength(1)->maxLength(50);
        $this->prop('surname')->string()->minLength(1)->maxLength(50);
        $this->prop('contact')->ref(Contact::class)->nullable();
        $this->prop('birthday')->dateTime('Y-m-d H:i:s');
        $this->prop('sex')->enum(['MALE', 'FEMALE']);
    }
}
