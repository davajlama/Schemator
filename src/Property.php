<?php

declare(strict_types=1);


namespace Davajlama\Schemator;

use Davajlama\Schemator\Rules\OneOf;
use Davajlama\Schemator\Rules\Rule;
use Davajlama\Schemator\Rules\RulesFactory;

class Property
{
    private RulesFactory $rulesFactory;

    private bool $required = true;

    /** @var Rule[] */
    private array $rules = [];

    public function __construct(RulesFactory $rulesFactory)
    {
        $this->rulesFactory = $rulesFactory;
    }

    public function required(bool $bool): self
    {
        $this->required = $bool;

        return $this;
    }

    public function string(?string $message = null): self
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

    public function integer(?string $message = null): self
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

    public function oneOf(array $values, ?string $message = null): self
    {
        $rule = $this->rulesFactory->createOneOf($values, $message);
        $this->addRule($rule);

        return $this;
    }

    public function boolean(?string $message = null): self
    {
        $rule = $this->rulesFactory->createBooleanType($message);
        $this->addRule($rule);

        return $this;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function addRule(Rule $rule): void
    {
        $this->rules[] = $rule;
    }

    /**
     * @return Rule[]
     */
    public function getRules(): array
    {
        return $this->rules;
    }
}