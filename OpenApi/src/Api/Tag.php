<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi\Api;

use Davajlama\Schemator\OpenApi\DefinitionInterface;
use Davajlama\Schemator\OpenApi\PropertyHelper;

class Tag implements DefinitionInterface
{
    use PropertyHelper;

    private string $name;

    private ?string $description = null;

    private ?ExternalDocs $externalDocs = null;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function externalDocs(string $url): ExternalDocs
    {
        if ($this->externalDocs === null) {
            $this->externalDocs = new ExternalDocs($url);
        }

        return $this->externalDocs->url($url);
    }

    /**
     * @return mixed[]
     */
    public function build(): array
    {
        return $this->join(
            $this->prop('name', $this->name),
            $this->prop('description', $this->description),
            $this->prop('externalDocs', $this->externalDocs?->build()),
        );
    }
}
