<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi\Api;

use Davajlama\Schemator\OpenApi\DefinitionInterface;
use Davajlama\Schemator\OpenApi\PropertyHelper;

class License implements DefinitionInterface
{
    use PropertyHelper;

    private string $name;

    private ?string $url = null;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function url(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return mixed[]
     */
    public function build(): array
    {
        return $this->join(
            $this->prop('name', $this->name),
            $this->prop('url', $this->url),
        );
    }
}
