<?php

declare(strict_types=1);

namespace Schema;

use Davajlama\Schemator\Schema\Schema;

final class ArticlesFilter extends Schema
{
    public function __construct()
    {
        $this->prop('limit')->integer()->range(1, 100);
        $this->prop('weight')->integer()->max(99);
    }
}