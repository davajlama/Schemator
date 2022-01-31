<?php

declare(strict_types=1);


namespace Davajlama\Schemator;

use Davajlama\Schemator\Schema\SchemaProperty;

class Schema
{
    private Definition $definition;

    /** @var Schema[] */
    private array $references = [];

    /** @var SchemaProperty[] */
    private array $properties = [];

    private ?string $title = null;

    private ?string $description = null;

    /**
     * @param Definition $definition
     * @param Schema[] $references
     */
    public function __construct(Definition $definition, array $references = [])
    {
        $this->definition = $definition;
        $this->references = $references;

        foreach($definition->getProperties() as $name => $property) {
            $this->properties[$name] = new SchemaProperty($property);
        }
    }

    public function property(string $name): SchemaProperty
    {
        if (!array_key_exists($name, $this->properties)) {
            throw new \RuntimeException("Property [$name] not exists.");
        }

        return $this->properties[$name];
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
     * @return SchemaProperty[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @return Schema[]
     */
    public function getReferences(): array
    {
        return $this->references;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function isAdditionalPropertiesAllowed(): bool
    {
        return $this->definition->isAdditionalPropertiesAllowed();
    }

    public function getName(): ?string
    {
        return $this->definition->getName();
    }


}