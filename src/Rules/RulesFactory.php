<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Rules;


class RulesFactory
{
    public function createStringTypeRule(?string $message): StringTypeRule
    {
        return new StringTypeRule($message);
    }

    public function createCallbackRule(callable $callback, ?string $message): CallbackRule
    {
        return new CallbackRule($callback, $message);
    }

    public function createNonEmptyStringRule(?string $message): NonEmptyStringRule
    {
        return new NonEmptyStringRule($message);
    }
}