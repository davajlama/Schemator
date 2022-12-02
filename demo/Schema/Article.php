<?php

declare(strict_types=1);

namespace Schema;

use Davajlama\Schemator\Schema;

final class Article extends Schema
{
    public function __construct()
    {
        $this->prop('title')->string();
        $this->prop('description')->string();
    }
}