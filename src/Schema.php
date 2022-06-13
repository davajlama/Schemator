<?php

declare(strict_types=1);

namespace Davajlama\Schemator;

use Davajlama\Schemator\Rules\RulesFactory;

use function array_key_exists;

class Schema
{
    private ?RulesFactoryInterface $rulesFactory = null;

    private bool $additionalProperties = false;

    /**
     * @var Property[]
     */
    private array $properties = [];

    public function additionalProperties(bool $additionalProperties): self
    {
        $this->additionalProperties = $additionalProperties;

        return $this;
    }

    public function prop(string $name): Property
    {
        if (!array_key_exists($name, $this->properties)) {
            $this->properties[$name] = new Property($this->getRulesFactory());
        }

        return $this->properties[$name];
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
