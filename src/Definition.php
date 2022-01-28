<?php

declare(strict_types=1);

namespace Davajlama\Schemator;

use Davajlama\Schemator\Rules\RulesFactory;

class Definition
{
    /**
     * @var Property[]
     */
    private array $properties = [];

    private RulesFactory $rulesFactory;

    private bool $additionalProperties = false;

    private ?string $name = null;

    public function __construct(RulesFactory $rulesFactory, string $name = null)
    {
        $this->rulesFactory = $rulesFactory;
        $this->name = $name;
    }

    public function additionalProperties(bool $allowed): self
    {
        $this->additionalProperties = $allowed;

        return $this;
    }

    public function property(string $name, bool $required = false, Definition $definition = null): Property
    {
        if(array_key_exists($name, $this->properties)) {
            throw new \InvalidArgumentException('Property exists');
        }

        if ($definition === null) {
            $property = new Property($this->rulesFactory, $required);
        } else {
            if($definition->getName() === null) {
                throw new \RuntimeException('Referenced definition must have a name');
            }

            $property = new ReferencedProperty($this->rulesFactory, $required, $definition);
        }

        return $this->properties[$name] = $property;
    }

    public function isAdditionalPropertiesAllowed(): bool
    {
        return $this->additionalProperties;
    }

    /**
     * @return Property[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }
}