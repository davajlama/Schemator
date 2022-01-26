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

    public function __construct(RulesFactory $rulesFactory)
    {
        $this->rulesFactory = $rulesFactory;
    }

    public function property(string $name): Property
    {
        if(array_key_exists($name, $this->properties)) {
            throw new \InvalidArgumentException('Property exists');
        }

        return $this->properties[$name] = new Property($this->rulesFactory);
    }

    public function buildRules(): Rules
    {
        $rules = array_map(fn(Property $prop) => $prop->getRules(), $this->properties);
        return new Rules($rules);
    }
}