<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaConditions;

use Davajlama\Schemator\SchemaConditions\Conditions\ConditionsFactory;
use LogicException;

use function sprintf;

/**
 * @method self requiredIfAllNull(string ...$targetProperties)
 */
class PropertiesGroup
{
    /**
     * @var string[]
     */
    private array $properties;

    private ConditionsFactory $conditionsFactory;

    /**
     * @var ConditionInterface[]
     */
    private array $conditions = [];

    /**
     * @param string[] $properties
     */
    public function __construct(array $properties, ?ConditionsFactory $conditionsFactory = null)
    {
        $this->properties = $properties;
        $this->conditionsFactory = $conditionsFactory ?? new ConditionsFactory();
    }

    public function condition(ConditionInterface $condition): self
    {
        $this->conditions[] = $condition;

        return $this;
    }

    /**
     * @return ConditionInterface[]
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    /**
     * @param mixed[] $arguments
     */
    public function __call(string $name, array $arguments): self
    {
        $condition = $this->conditionsFactory->create($name, [$this->properties, $arguments]);
        if ($condition === null) {
            throw new LogicException(sprintf('Filter %s not exists.', $name));
        }

        return $this->condition($condition);
    }
}
