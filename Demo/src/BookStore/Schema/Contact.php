<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Demo\BookStore\Schema;

use Davajlama\Schemator\Schema\Schema;

final class Contact extends Schema
{
    public function __construct()
    {
        parent::__construct();

        $this->requiredDefaultValue(true);
        $this->prop('id')->integer()->examples(1, 2, 3);
        $this->prop('email')->email()->examples('john@doe.com', 'foobar+256@gmail.com');
        $this->prop('phone')->string()
            ->example('+420789456132')
            ->description('The phone number with county prefix.');
    }
}
