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
        $rule = $this->rulesFactory->createStringType($message);
        $this->addRule($rule);

        return $this;
    }

    public function nullableString(?string $messasge = null): self
    {
        $rule = $this->rulesFactory->createNullableString($messasge);
        $this->addRule($rule);

        return $this;
    }

    public function integerType(?string $message = null): self
    {
        $rule = $this->rulesFactory->createIntegerType($message);
        $this->addRule($rule);

        return $this;
    }

    public function nullableInteger(?string $message = null): self
    {
        $rule = $this->rulesFactory->createNullableInteger($message);
        $this->addRule($rule);

        return $this;
    }

    public function callback(callable $callback, ?string $message = null): self
    {
        $rule = $this->rulesFactory->createCallback($callback, $message);
        $this->addRule($rule);

        return $this;
    }

    public function nonEmptyString(?string $message = null): self
    {
        $rule = $this->rulesFactory->createNonEmptyString($message);
        $this->addRule($rule);

        return $this;
    }

    public function arrayOf(Definition $definition, ?string $message = null): self
    {
        $rule = $this->rulesFactory->createArrayOf($definition, $message);
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