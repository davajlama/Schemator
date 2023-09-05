<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Arr\Exception;

use LogicException;

final class ArrException extends LogicException
{
    public static function propertyIsNotArray(): self
    {
        return new self('Property is not an array.');
    }

    public static function propertyNotExists(): self
    {
        return new self('Property not exists.');
    }

    public static function invalidPropertyFormat(): self
    {
        return new self('Invalid property format.');
    }
}
