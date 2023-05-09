<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaConditions\Conditions;

use Davajlama\Schemator\SchemaConditions\ConditionInterface;

use function class_exists;
use function ucfirst;

class ConditionsFactory
{
    /**
     * @param mixed[] $arguments
     */
    public function create(string $name, array $arguments): ?ConditionInterface
    {
        $class = 'Davajlama\Schemator\SchemaConditions\Conditions\\' . ucfirst($name);
        if (class_exists($class)) {
            /** @var ConditionInterface $condition */
            $condition = new $class(...$arguments);

            return $condition;
        }

        return null;
    }
}
