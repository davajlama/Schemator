<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Validator;

final class PropertyError
{
    private Message $message;

    private string $property;

    /**
     * @var string[]
     */
    private array $path;

    private ?int $index;

    /**
     * @var PropertyError[]
     */
    private array $errors;

    /**
     * @param string[] $path
     * @param self[] $errors
     */
    public function __construct(Message $message, string $property, array $path = [], ?int $index = null, array $errors = [])
    {
        $this->message = $message;
        $this->property = $property;
        $this->path = $path;
        $this->errors = $errors;
        $this->index = $index;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }

    public function getProperty(): string
    {
        return $this->property;
    }

    /**
     * @return string[]
     */
    public function getPath(): array
    {
        return $this->path;
    }

    public function getIndex(): ?int
    {
        return $this->index;
    }

    /**
     * @return PropertyError[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
