<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Rules;

use function strlen;
use function trim;

class NonEmptyStringRule extends StringTypeRule
{
    public function validateValue($value): void
    {
        parent::validateValue($value);

        if (strlen(trim($value)) === 0) {
            $this->fail('String cannot be empty!');
        }
    }
}
