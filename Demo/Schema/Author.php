<?php

declare(strict_types=1);

namespace Schema;

use Davajlama\Schemator\Schema;

final class Author extends Schema
{
    public function __construct()
    {
        $this->prop('firstname')->string();
        $this->prop('lastname')->string();
        $this->prop('email')->email();
    }
}