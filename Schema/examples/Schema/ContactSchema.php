<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Examples\Schema;

use Davajlama\Schemator\Schema\Schema;

final class ContactSchema extends Schema
{
    public function __construct()
    {
        parent::__construct();

        $this->additionalProperties(false);
        $this->prop('firstname')->string()->required();
        $this->prop('surname')->string()->required();
        $this->prop('age')->integer()->required();
    }
}
