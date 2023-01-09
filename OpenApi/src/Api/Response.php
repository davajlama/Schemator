<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi\Api;

use Davajlama\Schemator\OpenApi\DefinitionInterface;
use Davajlama\Schemator\OpenApi\PropertyHelper;

class Response implements DefinitionInterface
{
    use PropertyHelper;

    private int $status;

    private ?string $description = null;

    private ?Content $content = null;

    public function __construct(int $status)
    {
        $this->status = $status;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function build(): array
    {
        return [
            $this->status => $this->join([
                $this->prop('description', $this->description)
            ]),
        ];
    }

    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function content(string|Content $content): self
    {

    }
}