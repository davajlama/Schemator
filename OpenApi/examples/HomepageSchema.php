<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi\Examples;

use Davajlama\Schemator\Schema\Schema;

final class HomepageSchema extends Schema
{
    public function __construct()
    {
        $this->prop('version')->integer()->title('Only for information.')->nullable();
        $this->prop('title')->string();
        $this->prop('content')->string();
    }
}
