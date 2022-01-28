<?php

declare(strict_types=1);


namespace Davajlama\Schemator;

use Davajlama\Schemator\Schema\SchemaProperty;

class Schema
{
    private Definition $definition;

    /** @var SchemaProperty[] */
    private array $properties = [];

    /** @var Schema[] */
    private array $definitions = [];

    /** @var Schema[] */
    private array $namedDefinitions = [];

    private ?string $title = null;

    private ?string $description = null;

    private ?string $name;

    private bool $additionalProperties;

    /**
     * @param Definition $definition
     * @param Schema[] $referencedDefinitions
     */
    public function __construct(Definition $definition, array $referencedDefinitions = [])
    {
        $this->definition = $definition;

        $this->name = $definition->getName();
        $this->additionalProperties = $definition->isAdditionalPropertiesAllowed();

        foreach($referencedDefinitions as $referencedDefinition) {
            if($referencedDefinition->getName() === null) {
                throw new \RuntimeException('Referenced definitions must be named.');
            }

            if(array_key_exists($referencedDefinition->getName(), $this->namedDefinitions)) {
                throw new \RuntimeException(sprintf(
                    'Referenced definition %s already exits.',
                    $referencedDefinition->getName(),
                ));
            }

            $this->namedDefinitions[$referencedDefinition->getName()] = $referencedDefinition;
        }

        foreach($definition->getProperties() as $name => $property) {
            $this->properties[$name] = new SchemaProperty($property);

            if($property instanceof ReferencedProperty) {
                $referencedDefinition = $property->getReferencedDefinition();
                if($referencedDefinition->getName() !== null) {
                    if(!array_key_exists($referencedDefinition->getName(), $this->namedDefinitions)) {
                        $this->namedDefinitions[$referencedDefinition->getName()] = new self($referencedDefinition);
                    }

                    $this->definitions[$name] = $this->namedDefinitions[$referencedDefinition->getName()];
                } else {
                    $this->definitions[$name] = new self($property->getReferencedDefinition());
                }
            }
        }
    }

    public function property(string $name): SchemaProperty
    {
        if (!array_key_exists($name, $this->properties)) {
            throw new \RuntimeException("Property [$name] not exists.");
        }

        return $this->properties[$name];
    }

    public function definition(string $name): Schema
    {
        if (!array_key_exists($name, $this->definitions)) {
            throw new \RuntimeException("Definition [$name] not exists.");
        }

        return $this->definitions[$name];
    }

    public function schema(string $name): Schema
    {
        foreach($this->definitions as $definition) {
            if($definition->getName() === $name) {
                return $definition;
            }
        }

        throw new \RuntimeException("Schema [$name] not exists.");
    }

    public function getDefinition(): Definition
    {
        return $this->definition;
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
        return $this->additionalProperties;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}