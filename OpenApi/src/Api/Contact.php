<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi\Api;

use Davajlama\Schemator\OpenApi\DefinitionInterface;
use Davajlama\Schemator\OpenApi\PropertyHelper;

class Contact implements DefinitionInterface
{
    use PropertyHelper;

    private ?string $name = null;

    private ?string $url = null;

    private ?string $email = null;

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function url(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function email(string $email): self
    {
        $this->email = $email;

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
            $this->prop('email', $this->email),
        );
    }
}
