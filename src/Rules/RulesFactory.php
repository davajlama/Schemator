<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Rules;


class RulesFactory
{
    public function createStringType(?string $message): StringTypeRule
    {
        return new StringTypeRule($message);
    }

    public function createNullableString(?string $message): NullableString
    {
        return new NullableString($message);
    }

    public function createIntegerType(?string $message): IntegerType
    {
        return new IntegerType($message);
    }

    public function createNullableInteger(?string $message): NullableInteger
    {
        return new NullableInteger($message);
    }

    public function createCallback(callable $callback, ?string $message): CallbackRule
    {
        return new CallbackRule($callback, $message);
    }

    public function createNonEmptyString(?string $message): NonEmptyStringRule
    {
        return new NonEmptyStringRule($message);
    }
}