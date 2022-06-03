<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Rules;

use Davajlama\Schemator\RuleInterface;
use Davajlama\Schemator\RulesFactoryInterface;
use LogicException;

use function class_exists;
use function sprintf;
use function ucfirst;

class RulesFactory implements RulesFactoryInterface
{
    /**
     * @param mixed[] $arguments
     */
    public function create(string $name, array $arguments): RuleInterface
    {
        $class = 'Davajlama\Schemator\Rules\\' . ucfirst($name);
        if (class_exists($class)) {
            /** @var RuleInterface $rule */
            $rule = new $class(...$arguments);

            return $rule;
        }

        $class = 'Davajlama\Schemator\Rules\\' . ucfirst($name) . 'Type';
        if (class_exists($class)) {
            /** @var RuleInterface $rule */
            $rule = new $class(...$arguments);

            return $rule;
        }

        throw new LogicException(sprintf('Rule %s not exists.', $name));
    }
}
