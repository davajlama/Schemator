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

    private bool $additionalProperties;

    public function __construct(RulesFactory $rulesFactory, bool $additionalProperties = false)
    {
        $this->rulesFactory = $rulesFactory;
        $this->additionalProperties = $additionalProperties;
    }

    public function property(string $name, bool $required = false): Property
    {
        if(array_key_exists($name, $this->properties)) {
            throw new \InvalidArgumentException('Property exists');
        }

        return $this->properties[$name] = new Property($this->rulesFactory, $required);
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