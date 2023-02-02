<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi\Api;

use Davajlama\Schemator\Schema\Schema;

class JsonContent extends Content
{
    public function __construct(Schema $schema)
    {
        parent::__construct('application/json');

        $this->schema($schema);
    }
}
