<?php

declare(strict_types=1);

final class HomepageSchema extends Davajlama\Schemator\Schema\Schema
{
    public function __construct()
    {
        $this->prop('version')->integer()->title('Only for information.')->nullable();
        $this->prop('title')->string();
        $this->prop('content')->string();
    }
}
