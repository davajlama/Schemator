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
        $rule = $this->rulesFactory->createStringTypeRule($message);
        $this->addRule($rule);

        return $this;
    }

    public function callback(callable $callback, ?string $message = null): self
    {
        $rule = $this->rulesFactory->createCallbackRule($callback, $message);
        $this->addRule($rule);

        return $this;
    }

    public function nonEmptyString(?string $message = null): self
    {
        $rule = $this->rulesFactory->createNonEmptyStringRule($message);
        $this->addRule($rule);

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

    protected function addRule(Rule $rule): void
    {
        $this->rules[] = $rule;
    }

}