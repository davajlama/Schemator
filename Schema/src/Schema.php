<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema;

use Davajlama\Schemator\Schema\Rules\RulesFactory;

use function array_key_exists;

class Schema
{
    private ?string $name = null;

    private ?RulesFactoryInterface $rulesFactory = null;

    private bool $additionalProperties = false;

    /**
     * @var Property[]
     */
    private array $properties = [];

    public function __construct(?string $name = null)
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function additionalProperties(bool $additionalProperties): self
    {
        $this->additionalProperties = $additionalProperties;

        return $this;
    }

    public function prop(string $name): Property
    {
        return $this->registerProperty($name);
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

    protected function registerProperty(string $name, string $class = Property::class): Property
    {
        if (!array_key_exists($name, $this->properties)) {
            /** @var Property $property */
            $property = new $class($this->getRulesFactory());

            $this->properties[$name] = $property;
        }

        return $this->properties[$name];
    }

    protected function getRulesFactory(): RulesFactoryInterface
    {
        if ($this->rulesFactory === null) {
            $this->rulesFactory = new RulesFactory();
        }

        return $this->rulesFactory;
    }

    protected function setRulesFactory(RulesFactoryInterface $rulesFactory): void
    {
        $this->rulesFactory = $rulesFactory;
    }
}
