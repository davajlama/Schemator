<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Demo\BookStore\Schema;

use Davajlama\Schemator\Schema\Schema;

final class Product extends Schema
{
    public function __construct()
    {
        parent::__construct();

        $this->requiredDefaultValue(true);
        $this->prop('id')->integer();
        $this->prop('name')->string()->minLength(3)->maxLength(255);
        $this->prop('description')->string()->nullable();
        $this->prop('price')->float();
        $this->prop('store')->integer();
        $this->prop('authors')->arrayOf(Author::class)->minItems(1)->unique();
        $this->prop('attributes')->arrayOf(Attribute::class)->unique();
        $this->prop('images')->arrayOf(Image::class);
        $this->prop('type')->enum(['BOOK', 'MAGAZINE']);
        $this->prop('rating')->range(0, 100);
    }
}
