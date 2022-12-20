<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Examples\Schema;

use Davajlama\Schemator\Schema\Schema;

final class PhotoSchema extends Schema
{
    public function __construct()
    {
        $this->additionalProperties(false);
        $this->prop('url')->string()->required();
        $this->prop('description')->string()->required();
    }
}
