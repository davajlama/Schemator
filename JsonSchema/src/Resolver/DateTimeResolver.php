<?php

declare(strict_types=1);

namespace Davajlama\JsonSchemaGenerator\Resolver;

use Davajlama\JsonSchemaGenerator\Definition;
use Davajlama\JsonSchemaGenerator\ReflectionExtractor;
use Davajlama\Schemator\RuleInterface;
use Davajlama\Schemator\Rules\DateTime;


final class DateTimeResolver implements ResolverInterface
{
    public function support(RuleInterface $rule): bool
    {
        return $rule instanceof DateTime;
    }

    public function resolve(Definition $definition, RuleInterface $rule): void
    {
        $schemaFormat = 'date-time';

        $format = ReflectionExtractor::getProperty($rule, 'format');
        if ($format !== null) {
            $schemaFormat = $format;
        }

        // @todo
        $definition->setFormat($schemaFormat);
    }
}