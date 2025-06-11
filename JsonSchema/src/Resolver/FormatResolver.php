<?php

declare(strict_types=1);

namespace Davajlama\Schemator\JsonSchema\Resolver;

use Davajlama\Schemator\JsonSchema\Definition;
use Davajlama\Schemator\Schema\RuleInterface;
use Davajlama\Schemator\Schema\Rules\Email;
use Davajlama\Schemator\Schema\Rules\Url;

final class FormatResolver implements ResolverInterface
{
    public function support(RuleInterface $rule): bool
    {
        return $rule instanceof Email
            || $rule instanceof Url;
    }

    public function resolve(Definition $definition, RuleInterface $rule): void
    {
        if ($rule instanceof Email) {
            $definition->setFormat('email');
        } elseif ($rule instanceof Url) {
            $definition->setFormat('uri');
        }
    }
}
