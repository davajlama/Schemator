<?php

declare(strict_types=1);

namespace Davajlama\Schemator\JsonSchema\Resolver;

use Davajlama\Schemator\JsonSchema\Definition;
use Davajlama\Schemator\JsonSchema\ReflectionExtractor;
use Davajlama\Schemator\Schema\RuleInterface;
use Davajlama\Schemator\Schema\Rules\DateTime;

final class DateTimeResolver implements ResolverInterface
{
    public function support(RuleInterface $rule): bool
    {
        return $rule instanceof DateTime;
    }

    public function resolve(Definition $definition, RuleInterface $rule): void
    {
        $schemaFormat = 'date-time';

        /** @var ?string $format */
        $format = ReflectionExtractor::getProperty($rule, 'format');
        if ($format !== null) {
            $schemaFormat = $format;
        }

        // @todo
        $definition->setFormat($schemaFormat);
    }
}
