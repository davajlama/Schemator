<?php

declare(strict_types=1);

namespace Davajlama\Schemator;

use LogicException;

use function count;

/**
 * @method self string(?string $message = null)
 * @method self integer(?string $message = null)
 */
class Property
{
    private RulesFactoryInterface $rulesFactory;

    private ?Schema $reference = null;

    private bool $required = false;

    /**
     * @var RuleInterface[]
     */
    private array $rules = [];

    public function __construct(RulesFactoryInterface $rulesFactory)
    {
        $this->rulesFactory = $rulesFactory;
    }

    public function rule(RuleInterface $rule): self
    {
        if ($this->reference !== null) {
            throw new LogicException('Cannot assignee reference when rules not empty.');
        }

        $this->rules[] = $rule;

        return $this;
    }

    public function required(bool $required = true): self
    {
        $this->required = $required;

        return $this;
    }

    public function ref(?Schema $schema): self
    {
        if (count($this->rules) > 0) {
            throw new LogicException('Cannot add rule when reference was assigned.');
        }

        $this->reference = $schema;

        return $this;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @return RuleInterface[]
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    public function getReference(): ?Schema
    {
        return null;
    }

    /**
     * @param mixed[] $arguments
     */
    public function __call(string $name, array $arguments): self
    {
        $this->rules[] = $this->rulesFactory->create($name, $arguments);

        return $this;
    }
}
