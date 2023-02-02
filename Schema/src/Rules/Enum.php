<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Rules;

use Davajlama\Schemator\Schema\Exception\ValidationFailedException;

use function array_map;
use function implode;
use function in_array;
use function sprintf;

class Enum extends BaseRule
{
    /**
     * @var scalar[]
     */
    private array $values;

    /**
     * @param scalar[] $values
     */
    public function __construct(array $values, ?string $message = null)
    {
        parent::__construct($message);

        $this->values = $values;
    }

    public function validateValue(mixed $value): void
    {
        if (!in_array($value, $this->values, true)) {
            throw new ValidationFailedException(
                sprintf(
                    $this->getMessage('Must be one of [%s]'),
                    implode(', ', array_map(static fn($v) => (string) $v, $this->values)),
                )
            );
        }
    }
}
