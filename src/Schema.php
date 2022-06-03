<?php

declare(strict_types=1);

namespace Davajlama\Schemator;

use Davajlama\Schemator\Rules\RulesFactory;

use function array_key_exists;

class Schema
{
    private RulesFactoryInterface $rulesFactory;

    private bool $additionalProperties = true;

    /**
     * @var Property[]
     */
    private array $properties = [];

    public function __construct(?RulesFactoryInterface $rulesFactory = null)
    {
        $this->rulesFactory = $rulesFactory ?? new RulesFactory();
    }

    public function additionalProperties(bool $additionalProperties): self
    {
        $this->additionalProperties = $additionalProperties;

        return $this;
    }

    public function prop(string $name): Property
    {
        if (!array_key_exists($name, $this->properties)) {
            $this->properties[$name] = new Property($this->rulesFactory);
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
}
