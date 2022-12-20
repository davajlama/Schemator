<?php

declare(strict_types=1);

namespace Davajlama\JsonSchemaGenerator\Resolver;

use Davajlama\JsonSchemaGenerator\Definition;
use Davajlama\JsonSchemaGenerator\ReflectionExtractor;
use Davajlama\Schemator\Schema\RuleInterface;
use Davajlama\Schemator\Schema\Rules\Length;
use Davajlama\Schemator\Schema\Rules\MaxLength;
use Davajlama\Schemator\Schema\Rules\MinLength;

final class LengthResolver implements ResolverInterface
{
    public function support(RuleInterface $rule): bool
    {
        return $rule instanceof Length
            || $rule instanceof MinLength
            || $rule instanceof MaxLength;
    }

    public function resolve(Definition $definition, RuleInterface $rule): void
    {
        if ($rule instanceof Length) {
            /** @var int $length */
            $length = ReflectionExtractor::getProperty($rule, 'length');

            $definition->setMinLength($length);
            $definition->setMaxLength($length);
        } else if ($rule instanceof MinLength) {
            /** @var int $minLength */
            $minLength = ReflectionExtractor::getProperty($rule, 'minLength');

            $definition->setMinLength($minLength);
        } else if ($rule instanceof MaxLength) {
            /** @var int $maxLength */
            $maxLength = ReflectionExtractor::getProperty($rule, 'maxLength');

            $definition->setMaxLength($maxLength);
        }
    }
}