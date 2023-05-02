<?php

declare(strict_types=1);

namespace Davajlama\Schemator\JsonSchema\Resolver;

use Davajlama\Schemator\JsonSchema\Definition;
use Davajlama\Schemator\JsonSchema\ReflectionExtractor;
use Davajlama\Schemator\Schema\RuleInterface;
use Davajlama\Schemator\Schema\Rules\ArrayOfValues;

use function array_unique;
use function gettype;

final class ArrayOfValuesResolver implements ResolverInterface
{
    public function support(RuleInterface $rule): bool
    {
        return $rule instanceof ArrayOfValues;
    }

    public function resolve(Definition $definition, RuleInterface $rule): void
    {
        /** @var scalar[] $values */
        $values = ReflectionExtractor::getProperty($rule, 'values');

        $valuesDefinition = new Definition();
        $valuesDefinition->setEnum($values);

        foreach ($this->getTypes($values) as $type) {
            $valuesDefinition->addType($type);
        }

        $definition->addType('array');
        $definition->setItems($valuesDefinition);
    }

    /**
     * @param mixed[] $values
     * @return string[]
     */
    private function getTypes(array $values): array
    {
        $types = [];
        foreach ($values as $value) {
            $types[] = gettype($value);
        }

        return array_unique($types);
    }
}
