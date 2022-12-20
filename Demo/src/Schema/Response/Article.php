<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Demo\Schema\Response;

use Davajlama\Schemator\Schema\Schema;

final class Article extends Schema
{
    public function __construct()
    {
        $this->prop('title')->string()->minLength(1)->maxLength(255);
        $this->prop('description')->string()->maxLength(2048)->nullable();
        $this->prop('bestseller')->bool();
        $this->prop('pages')->integer()->min(1);
        $this->prop('published')->string()->dateTime('Y-m-d h:i:s');
        $this->prop('tags')->arrayOfString()->unique();
        $this->prop('author')->arrayOf(Author::class)->minItems(1);
        $this->prop('weight')->float();
        $this->prop('flag')->enum(['NEW', 'ACTION']);
        $this->prop('ean')->string()->length(16);

    }
}