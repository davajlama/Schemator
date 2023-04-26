<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Demo\BookStore\Schema;

use Davajlama\Schemator\Schema\Schema;

final class Attribute extends Schema
{
    public function __construct()
    {
        parent::__construct();

        $this->requiredDefaultValue(true);
        $this->prop('id')->integer();
        $this->prop('name')->string();
        $this->prop('params')->dynamicObject();
        $this->prop('created')->dateTime('Y-m-d H:i:s');
    }
}
