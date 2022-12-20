<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Demo\Schema\Response;

use Davajlama\Schemator\Schema\Schema;

final class Author extends Schema
{
    public function __construct()
    {
        $this->prop('firstname')->string();
        $this->prop('lastname')->string();
        $this->prop('email')->email();
    }
}