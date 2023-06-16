<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Exception;

use Davajlama\Schemator\Schema\Validator\Message;
use Davajlama\Schemator\Schema\Validator\PropertyError;

final class PropertyIsNotStringException extends ValidationFailedException
{
    /**
     * @param PropertyError[] $errors
     */
    public function __construct(array $errors = [])
    {
        parent::__construct(new Message('Must be a string.'), $errors);
    }
}
