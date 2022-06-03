<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Examples\Schema;

use Davajlama\Schemator\Schema;

final class LetterSchema extends Schema
{
    public function __construct()
    {
        parent::__construct();

        $contactSchema = new ContactSchema();
        $this->additionalProperties(false);
        $this->prop('from')->ref($contactSchema)->required();
        $this->prop('to')->ref($contactSchema)->required();
        $this->prop('message')->string()->required();
        $this->prop('photos')->oneOf(new PhotoSchema());
    }
}