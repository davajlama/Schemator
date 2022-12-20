<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Examples\Schema;

use Davajlama\Schemator\Schema\Schema;

final class LetterSchema extends Schema
{
    public function __construct()
    {
        $this->additionalProperties(false);
        $this->prop('from')->ref(ContactSchema::class)->required();
        $this->prop('to')->ref(ContactSchema::class)->required();
        $this->prop('subject')->string()->required()->nullable();
        $this->prop('message')->string()->required();
        $this->prop('photos')->arrayOf(PhotoSchema::class);
    }
}