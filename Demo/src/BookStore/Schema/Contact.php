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
        $this->prop('id')->integer();
        $this->prop('email')->email();
        $this->prop('phone')->string();
    }
}
