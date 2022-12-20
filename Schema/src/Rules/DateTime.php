<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Rules;

use Davajlama\Schemator\Schema\Exception\PropertyIsNotStringException;

use function is_string;
use function sprintf;

final class DateTime extends BaseRule
{
    private ?string $format;

    public function __construct(?string $format = null, ?string $message = null)
    {
        parent::__construct($message);

        $this->format = $format;
    }

    public function validateValue(mixed $value): void
    {
        if (!is_string($value)) {
            throw new PropertyIsNotStringException();
        }

        $format = $this->format;
        if ($this->format === null) {
            $format = \DateTime::RFC3339;
        }

        $dt = \DateTime::createFromFormat($format, $value);
        if ($dt === false) {
            $this->fail($this->getMessage(sprintf('Invalid format %s.', $this->format)));
        }
    }
}
