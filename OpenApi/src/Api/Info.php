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

    private ?string $termsOfService = null;

    private ?Contact $contact = null;

    private ?License $license = null;

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

    public function termsOfService(string $termsOfService): self
    {
        $this->termsOfService = $termsOfService;

        return $this;
    }

    public function contact(): Contact
    {
        if ($this->contact === null) {
            $this->contact = new Contact();
        }

        return $this->contact;
    }

    public function license(string $name): License
    {
        if ($this->license === null) {
            $this->license = new License($name);
        }

        return $this->license;
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
            $this->prop('termsOfService', $this->termsOfService),
            $this->prop('contact', $this->contact?->build()),
            $this->prop('license', $this->license?->build()),
        );
    }
}
