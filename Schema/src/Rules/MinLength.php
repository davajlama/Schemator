<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Rules;

use Davajlama\Schemator\Schema\Exception\ValidationFailedException;

use function is_string;
use function sprintf;
use function strlen;

class MinLength extends BaseRule
{
    private int $minLength;

    public function __construct(int $minLength, ?string $message = null)
    {
        parent::__construct($message);

        $this->minLength = $minLength;
    }

    public function validateValue(mixed $value): void
    {
        if (!is_string($value)) {
            throw new ValidationFailedException('Must by a string.');
        }

        if ($this->minLength > strlen($value)) {
            throw new ValidationFailedException($this->getMessage(sprintf('Must be min %d chars length.', $this->minLength)));
        }
    }
}
