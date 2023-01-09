<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi\Api;

class Content
{
    private string $type;

    private ?string $ref;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }

}