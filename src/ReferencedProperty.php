<?php

declare(strict_types=1);

namespace Davajlama\Schemator;

use Davajlama\Schemator\Rules\Rule;
use Davajlama\Schemator\Rules\RulesFactory;

class ReferencedProperty extends Property
{
    private Definition $reference;

    public function __construct(RulesFactory $rulesFactory, Definition $reference)
    {
        parent::__construct($rulesFactory);

        $this->reference = $reference;
    }

    public function getReferencedDefinition(): Definition
    {
        return $this->reference;
    }

    public function addRule(Rule $rule): void
    {
        throw new \LogicException('Cannot add rule to referenced property');
    }
}