<?php

declare(strict_types=1);

namespace Davajlama\Schemator;

use LogicException;

use function count;
use function sprintf;

/**
 * @method self string(?string $message = null)
 * @method self integer(?string $message = null)
 * @method self callback(callable $callback)
 * @method self oneOf(Schema|string $schema, ?string $message = null)
 */
class Property
{
    use SchemaFactoryHelper;

    private RulesFactoryInterface $rulesFactory;

    private ?Schema $reference = null;

    private bool $required = false;

    private bool $nullable = false;

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
            throw new LogicException('Cannot add rule when reference was assigned.');
        }

        $this->rules[] = $rule;

        return $this;
    }

    public function required(bool $required = true): self
    {
        $this->required = $required;

        return $this;
    }

    public function nullable(bool $nullable = true): self
    {
        $this->nullable = $nullable;

        return $this;
    }

    public function ref(Schema|string|null $schema): self
    {
        if (count($this->rules) > 0) {
            throw new LogicException('Cannot assignee reference when rules not empty.');
        }

        if ($schema !== null) {
            $schema = $this->createSchema($schema);
        }

        $this->reference = $schema;

        return $this;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
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
        return $this->reference;
    }

    /**
     * @param mixed[] $arguments
     */
    public function __call(string $name, array $arguments): self
    {
        $rule = $this->rulesFactory->create($name, $arguments);
        if ($rule === null) {
            throw new LogicException(sprintf('Rule %s not exists.', $name));
        }

        $this->rules[] = $rule;

        return $this;
    }
}
