<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Arr\Exception;

use LogicException;

use function is_string;
use function sprintf;

final class ArrException extends LogicException
{
    public static function propertyIsNotArray(string|int $key): self
    {
        $key = is_string($key) ? $key : 'index: ' . $key;
        return new self(sprintf('Property [%s] is not an array.', $key));
    }

    public static function propertyNotExists(string $key): self
    {
        return new self(sprintf('Property [%s] not exists.', $key));
    }
}
