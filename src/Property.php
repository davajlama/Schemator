<?php

declare(strict_types=1);


namespace Davajlama\Schemator;

use Davajlama\Schemator\Rules\Rule;
use Davajlama\Schemator\Rules\RulesFactory;

class Property
{
    private RulesFactory $rulesFactory;

    private bool $required;

    /** @var Rule[] */
    private array $rules = [];

    public function __construct(RulesFactory $rulesFactory, bool $required)
    {
        $this->rulesFactory = $rulesFactory;
        $this->required = $required;
    }

    public function stringType(?string $message = null): self
    {
        $this->rules[] = $this->rulesFactory->createStringTypeRule($message);

        return $this;
    }

    public function callback(callable $callback, ?string $message = null): self
    {
        $this->rules[] = $this->rulesFactory->createCallbackRule($callback, $message);

        return $this;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @return Rule[]
     */
    public function getRules(): array
    {
        return $this->rules;
    }

}