<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Rules;

use Davajlama\Schemator\Schema\Exception\ValidationFailedException;

use function is_string;
use function sprintf;
use function strlen;

class Length extends BaseRule
{
    private int $length;

    public function __construct(int $length, ?string $message = null)
    {
        parent::__construct($message);

        $this->length = $length;
    }

    public function validateValue(mixed $value): void
    {
        if (!is_string($value)) {
            throw new ValidationFailedException('Must by a string.');
        }

        if ($this->length !== strlen($value)) {
            throw new ValidationFailedException($this->getMessage(sprintf('Must be %d chars length.', $this->length)));
        }
    }
}
