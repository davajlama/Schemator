<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema;

use LogicException;

use function count;
use function sprintf;

/**
 * @method self string()
 * @method self integer()
 * @method self float()
 * @method self bool()
 * @method self array()
 * @method self arrayOfString()
 * @method self arrayOfInteger()
 * @method self arrayOfValues(array $values)
 * @method self dynamicObject()
 *
 * @method self enum(array $values)
 * @method self email()
 * @method self minLength(int $min)
 * @method self maxLength(int $max)
 * @method self length(int $length)
 * @method self min(float $min)
 * @method self max(float $max)
 * @method self range(float $min, float $max)
 *
 * @method self arrayOf(Schema|string $schema)
 * @method self maxItems(int $maxItems)
 * @method self minItems(int $minItems)
 * @method self unique()
 *
 * @method self callback(callable $callback)
 * @method self dateTime(string $format = null)
 */
class Property
{
    private RulesFactoryInterface $rulesFactory;

    private Schema|string|null $reference = null;

    private bool $required = false;

    private bool $nullable = false;

    private ?string $title = null;

    private ?string $description = null;

    /**
     * @var mixed[]|null
     */
    private ?array $examples = null;

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

        $this->reference = $schema;

        return $this;
    }

    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function examples(mixed ...$examples): self
    {
        $this->examples = $examples;

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

    public function getReference(): Schema|string|null
    {
        return $this->reference;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return mixed[]|null
     */
    public function getExamples(): ?array
    {
        return $this->examples;
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
