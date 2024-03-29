<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Rules;

use Davajlama\Schemator\Schema\Exception\PropertyIsNotArrayException;
use Davajlama\Schemator\Schema\Exception\ValidationFailedException;
use Davajlama\Schemator\Schema\Validator\Message;
use JsonException;

use function array_map;
use function array_unique;
use function count;
use function is_array;
use function json_encode;

final class Unique extends BaseRule
{
    public function validateValue(mixed $value): void
    {
        if (!is_array($value)) {
            throw new PropertyIsNotArrayException();
        }

        try {
            $uniques = array_unique(array_map(static fn($v) => json_encode($v, JSON_THROW_ON_ERROR), $value));

            if (count($value) !== count($uniques)) {
                throw new ValidationFailedException(new Message('Array contain non-unique values.'));
            }
        } catch (JsonException $e) {
            throw new ValidationFailedException(new Message('Array contain non-serializable values.'));
        }
    }
}
