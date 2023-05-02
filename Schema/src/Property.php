<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema;

use LogicException;

use function count;
use function sprintf;

/**
 * @method self string(?string $message = null)
 * @method self integer(?string $message = null)
 * @method self float(?string $message = null)
 * @method self bool(?string $message = null)
 * @method self array(?string $message = null)
 * @method self arrayOfString(?string $message = null)
 * @method self arrayOfInteger(?string $message = null)
 * @method self arrayOfValues(array $values, ?string $message = null)
 * @method self dynamicObject(?string $message = null)
 *
 * @method self enum(array $values, ?string $message = null)
 * @method self email(?string $message = null)
 * @method self minLength(int $min, ?string $message = null)
 * @method self maxLength(int $max, ?string $message = null)
 * @method self length(int $length, ?string $message = null)
 * @method self min(float $min, ?string $message = null)
 * @method self max(float $max, ?string $message = null)
 * @method self range(float $min, float $max, ?string $message = null)
 *
 * @method self arrayOf(Schema|string $schema, ?string $message = null)
 * @method self maxItems(int $maxItems, ?string $message = null)
 * @method self minItems(int $minItems, ?string $message = null)
 * @method self unique(?string $message = null)
 *
 * @method self callback(callable $callback)
 * @method self dateTime(string $format = null, ?string $message = null)
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
