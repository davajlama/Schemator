<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi\Api;

class JsonContent extends Content
{
    public function __construct()
    {
        parent::__construct('application/json');
    }
}