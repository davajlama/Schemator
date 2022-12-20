<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Demo\Schema\Response;

use Davajlama\Schemator\Schema\Schema;

final class Articles extends Schema
{
    public function __construct()
    {
        $this->prop('list')->arrayOf(Article::class)->maxItems(100);
    }
}