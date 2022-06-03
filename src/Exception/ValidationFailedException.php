<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Exception;

use Davajlama\Schemator\Validator\ErrorMessage;
use InvalidArgumentException;

final class ValidationFailedException extends InvalidArgumentException
{
    /**
     * @var ErrorMessage[]
     */
    private array $errors;

    /**
     * @param ErrorMessage[] $errors
     */
    public function __construct(string $message, array $errors = [])
    {
        parent::__construct($message);

        $this->errors = $errors;
    }

    /**
     * @return ErrorMessage[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
