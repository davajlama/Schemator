<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema;

use Davajlama\Schemator\Definition;
use Davajlama\Schemator\Property;
use Davajlama\Schemator\ReferencedProperty;
use Davajlama\Schemator\Rules\Rule;

class SchemaProperty
{
    /** @var Property | ReferencedProperty */
    private Property $property;

    private ?string $title = null;

    private ?string $description = null;

    /**
     * @var mixed[]
     */
    private array $examples = [];

    private bool $hidden = false;

    public function __construct(Property $property)
    {
        $this->property = $property;

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
     * @param mixed[] ...$examples
     */
    public function examples(...$examples): self
    {
        $this->examples = $examples;

        return $this;
    }

    public function hide(): self
    {
        $this->hidden = true;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return mixed[]
     */
    public function getExamples(): array
    {
        return $this->examples;
    }

    public function isHidden(): bool
    {
        return $this->hidden;
    }

    public function isRequired(): bool
    {
        return $this->property->isRequired();
    }

    public function isReferencedDefinition(): bool
    {
        return $this->property instanceof ReferencedProperty;
    }

    public function getReferencedDefinition(): Definition
    {
        if(!$this->isReferencedDefinition()) {
            throw new \RuntimeException('Property is not a referenced definition.');
        }

        return $this->property->getReferencedDefinition();
    }

    /**
     * @return Rule[]
     */
    public function getRules(): array
    {
        return $this->property->getRules();
    }

}