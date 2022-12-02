<?php

declare(strict_types=1);

namespace Schema;

use Davajlama\Schemator\Schema;

final class Articles extends Schema
{
    public function __construct()
    {
        $this->prop('list')->arrayOf(Article::class);
    }
}