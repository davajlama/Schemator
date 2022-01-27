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

    public function property(string $name, bool $required = false, Definition $definition = null): Property
    {
        if(array_key_exists($name, $this->properties)) {
            throw new \InvalidArgumentException('Property exists');
        }

        if ($definition === null) {
            $property = new Property($this->rulesFactory, $required);
        } else {
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
}