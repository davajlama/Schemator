<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Exception;

use Davajlama\Schemator\Validator\ErrorMessage;

final class PropertyIsNotStringException extends ValidationFailedException
{
    /**
     * @param ErrorMessage[] $errors
     */
    public function __construct(array $errors = [])
    {
        parent::__construct('Must be a string.', $errors);
    }
}
