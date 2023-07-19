<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Rules;

use Davajlama\Schemator\Schema\Exception\ValidationFailedException;
use Davajlama\Schemator\Schema\Validator\Message;

use function implode;
use function in_array;

class Enum extends BaseRule
{
    /**
     * @var scalar[]
     */
    private array $values;

    /**
     * @param scalar[] $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    public function validateValue(mixed $value): void
    {
        if (!in_array($value, $this->values, true)) {
            throw new ValidationFailedException(new Message('Must be one of [:values].', [':values' => implode('|', $this->values)]));
        }
    }
}
