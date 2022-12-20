<?php

declare(strict_types=1);

namespace Davajlama\JsonSchemaGenerator\Resolver;

use Davajlama\JsonSchemaGenerator\Definition;
use Davajlama\JsonSchemaGenerator\ReflectionExtractor;
use Davajlama\Schemator\RuleInterface;
use Davajlama\Schemator\Rules\Enum;

use function array_unique;
use function gettype;

final class EnumResolver implements ResolverInterface
{
    public function support(RuleInterface $rule): bool
    {
        return $rule instanceof Enum;
    }

    public function resolve(Definition $definition, RuleInterface $rule): void
    {
        $values = ReflectionExtractor::getProperty($rule, 'values');

        $definition->setEnum($values);
    }

    private function getTypes(array $values): array
    {
        $types = [];
        foreach ($values as $value) {
            $types[] = gettype($value);
        }

        return array_unique($types);
    }
}
