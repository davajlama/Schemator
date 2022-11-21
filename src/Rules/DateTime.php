<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Rules;

use Davajlama\Schemator\Exception\PropertyIsNotStringException;

use function is_string;
use function sprintf;

final class DateTime extends BaseRule
{
    private string $format;

    public function __construct(string $format, ?string $message = null)
    {
        parent::__construct($message);

        $this->format = $format;
    }

    public function validateValue(mixed $value): void
    {
        if (!is_string($value)) {
            throw new PropertyIsNotStringException();
        }

        $dt = \DateTime::createFromFormat($this->format, $value);
        if ($dt === false) {
            $this->fail($this->getMessage(sprintf('Invalid format %s.', $this->format)));
        }
    }
}
