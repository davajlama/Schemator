<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Examples\Schema;

use Davajlama\Schemator\Schema;

final class LetterSchema extends Schema
{
    public function __construct()
    {
        parent::__construct();

        $this->additionalProperties(false);
        $this->prop('from')->ref(ContactSchema::class)->required();
        $this->prop('to')->ref(ContactSchema::class)->required();
        $this->prop('message')->string()->required();
        $this->prop('photos')->oneOf(PhotoSchema::class);
    }
}