<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi\Api;

use Davajlama\Schemator\OpenApi\DefinitionInterface;
use Davajlama\Schemator\OpenApi\PropertyHelper;

class ExternalDocs implements DefinitionInterface
{
    use PropertyHelper;

    private string $url;

    private ?string $description = null;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function url(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed[]
     */
    public function build(): array
    {
        return $this->join(
            $this->prop('url', $this->url),
            $this->prop('description', $this->description),
        );
    }
}
