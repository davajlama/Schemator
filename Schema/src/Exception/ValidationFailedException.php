<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Exception;

use Davajlama\Schemator\Schema\Validator\ErrorMessage;
use InvalidArgumentException;

class ValidationFailedException extends InvalidArgumentException
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
