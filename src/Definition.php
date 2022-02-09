<?php

declare(strict_types=1);

namespace Davajlama\Schemator;

use Davajlama\Schemator\Rules\RulesFactory;
use InvalidArgumentException;
use RuntimeException;

use function array_key_exists;

class Definition
{
    /**
     * @var Property[]
     */
    private array $properties = [];

    private RulesFactory $rulesFactory;

    private bool $additionalProperties = false;

    private ?string $name = null;

    public function __construct(?string $name = null)
    {
        $this->rulesFactory = new RulesFactory();
        $this->name = $name;
    }

    public function additionalProperties(bool $allowed): self
    {
        $this->additionalProperties = $allowed;

        return $this;
    }

    public function property(string $name, ?Definition $definition = null): Property
    {
        if (array_key_exists($name, $this->properties)) {
            throw new InvalidArgumentException('Property exists');
        }

        if ($definition === null) {
            $property = new Property($this->rulesFactory);
        } else {
            if ($definition->getName() === null) {
                throw new RuntimeException('Referenced definition must have a name');
            }

            $property = new ReferencedProperty($this->rulesFactory, $definition);
        }

        return $this->properties[$name] = $property;
    }

    public function hasProperty(string $name): bool
    {
        return array_key_exists($name, $this->properties);
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

    public function getName(): ?string
    {
        return $this->name;
    }
}
