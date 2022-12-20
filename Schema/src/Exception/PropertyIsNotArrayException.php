<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Exception;

use Davajlama\Schemator\Schema\Validator\ErrorMessage;

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
