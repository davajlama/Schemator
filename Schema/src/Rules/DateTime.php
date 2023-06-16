<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Rules;

use Davajlama\Schemator\Schema\Exception\PropertyIsNotStringException;
use Davajlama\Schemator\Schema\Exception\ValidationFailedException;
use Davajlama\Schemator\Schema\Validator\Message;

use function is_string;

final class DateTime extends BaseRule
{
    private ?string $format;

    public function __construct(?string $format = null)
    {
        $this->format = $format;
    }

    public function validateValue(mixed $value): void
    {
        if (!is_string($value)) {
            throw new PropertyIsNotStringException();
        }

        $format = $this->format;
        if ($format === null) {
            $format = \DateTime::RFC3339;
        }

        $dt = \DateTime::createFromFormat($format, $value);
        if ($dt === false) {
            throw new ValidationFailedException(new Message('Invalid format :format.', [':format' => $format]));
        }
    }
}
