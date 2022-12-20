<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Exception;

use Davajlama\Schemator\Validator\ErrorMessage;

final class PropertyIsNotArrayException extends ValidationFailedException
{
    /**
     * @param ErrorMessage[] $errors
     */
    public function __construct(array $errors = [])
    {
        parent::__construct('Must be an array.', $errors);
    }
}
