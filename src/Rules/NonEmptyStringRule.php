<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Rules;

use Davajlama\Schemator\Exception\ValidationFailedException;

use function is_string;
use function strlen;
use function trim;

class NonEmptyStringRule extends BaseRule
{
    public function validateValue(mixed $value): void
    {
        if (!is_string($value)) {
            throw new ValidationFailedException('not a String');
        }

        if (strlen(trim($value)) === 0) {
            $this->fail('String cannot be empty!');
        }
    }
}
