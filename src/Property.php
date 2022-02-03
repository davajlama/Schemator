<?php

declare(strict_types=1);

namespace Davajlama\Schemator;

use Davajlama\Schemator\Rules\ArrayOf;
use Davajlama\Schemator\Rules\BooleanType;
use Davajlama\Schemator\Rules\CallbackRule;
use Davajlama\Schemator\Rules\IntegerType;
use Davajlama\Schemator\Rules\NonEmptyStringRule;
use Davajlama\Schemator\Rules\NullableInteger;
use Davajlama\Schemator\Rules\NullableString;
use Davajlama\Schemator\Rules\OneOf;
use Davajlama\Schemator\Rules\Rule;
use Davajlama\Schemator\Rules\RulesFactory;
use Davajlama\Schemator\Rules\StringTypeRule;

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
        $rule = $this->rulesFactory->create(StringTypeRule::class, $message);
        $this->addRule($rule);

        return $this;
    }

    public function nullableString(?string $message = null): self
    {
        $rule = $this->rulesFactory->create(NullableString::class, $message);
        $this->addRule($rule);

        return $this;
    }

    public function integer(?string $message = null): self
    {
        $rule = $this->rulesFactory->create(IntegerType::class, $message);
        $this->addRule($rule);

        return $this;
    }

    public function nullableInteger(?string $message = null): self
    {
        $rule = $this->rulesFactory->create(NullableInteger::class, $message);
        $this->addRule($rule);

        return $this;
    }

    public function callback(callable $callback, ?string $message = null): self
    {
        $rule = $this->rulesFactory->create(CallbackRule::class, $callback, $message);
        $this->addRule($rule);

        return $this;
    }

    public function nonEmptyString(?string $message = null): self
    {
        $rule = $this->rulesFactory->create(NonEmptyStringRule::class, $message);
        $this->addRule($rule);

        return $this;
    }

    public function arrayOf(Definition $definition, ?string $message = null): self
    {
        $rule = $this->rulesFactory->create(ArrayOf::class, $definition, $message);
        $this->addRule($rule);

        return $this;
    }

    public function oneOf(array $values, ?string $message = null): self
    {
        $rule = $this->rulesFactory->create(OneOf::class, $values, $message);
        $this->addRule($rule);

        return $this;
    }

    public function boolean(?string $message = null): self
    {
        $rule = $this->rulesFactory->create(BooleanType::class, $message);
        $this->addRule($rule);

        return $this;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function addRule(Rule $rule): self
    {
        $this->rules[] = $rule;

        return $this;
    }

    /**
     * @return Rule[]
     */
    public function getRules(): array
    {
        return $this->rules;
    }
}