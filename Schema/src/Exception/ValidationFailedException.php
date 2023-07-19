<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Exception;

use Davajlama\Schemator\Schema\Validator\Message;
use Davajlama\Schemator\Schema\Validator\PropertyError;
use InvalidArgumentException;

class ValidationFailedException extends InvalidArgumentException
{
    private Message $messageObject;

    /**
     * @var PropertyError[]
     */
    private array $errors;

    /**
     * @param PropertyError[] $errors
     */
    public function __construct(Message $messageObject, array $errors = [])
    {
        parent::__construct($messageObject->toString());

        $this->messageObject = $messageObject;
        $this->errors = $errors;
    }

    public function getMessageObject(): Message
    {
        return $this->messageObject;
    }

    /**
     * @return PropertyError[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
