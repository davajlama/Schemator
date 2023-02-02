<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi\Api;

use Davajlama\Schemator\OpenApi\DefinitionInterface;
use Davajlama\Schemator\OpenApi\PropertyHelper;

class Info implements DefinitionInterface
{
    use PropertyHelper;

    private ?string $version = null;

    private ?string $title = null;

    private ?string $description = null;

    public function version(string $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function title(string $title): self
    {
        $this->title = $title;

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
            $this->prop('version', $this->version),
            $this->prop('title', $this->title),
            $this->prop('description', $this->description),
        );
    }
}
