<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Rules;

use Davajlama\Schemator\Exception\ValidationFailedException;

use function is_string;
use function sprintf;
use function strlen;

class MaxLength extends BaseRule
{
    private int $maxLength;

    public function __construct(int $maxLength, ?string $message = null)
    {
        parent::__construct($message);

        $this->maxLength = $maxLength;
    }

    public function validateValue(mixed $value): void
    {
        if (!is_string($value)) {
            throw new ValidationFailedException('Must by a string.');
        }

        if ($this->maxLength < strlen($value)) {
            throw new ValidationFailedException($this->getMessage(sprintf('Must be max %d chars length.', $this->maxLength)));
        }
    }
}
