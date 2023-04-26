<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Demo\BookStore\Schema;

use Davajlama\Schemator\Schema\Schema;

final class Attributes extends Schema
{
    public function __construct()
    {
        parent::__construct();

        $this->prop('list')->arrayOf(Attribute::class)->required();
    }
}
