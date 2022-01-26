<?php

declare(strict_types=1);


namespace Davajlama\Schemator;

use Davajlama\Schemator\Rules\Rule;

class Rules
{
    /** @var Rule[] */
    private array $rules;

    /**
     * @param Rule[] $rules
     */
    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * @return Rule[]
     */
    public function toArray()
    {
        return $this->rules;
    }
}