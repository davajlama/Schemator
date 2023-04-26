<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Demo\BookStore\Schema;

use Davajlama\Schemator\Schema\Schema;

final class Image extends Schema
{
    public function __construct()
    {
        parent::__construct();

        $this->requiredDefaultValue(true);
        $this->prop('id')->integer();
        $this->prop('url')->string();
        $this->prop('title')->string()->nullable();
    }
}
