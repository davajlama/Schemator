<?php

declare(strict_types=1);


namespace Davajlama\Schemator;

final class ErrorMessage
{
    private string $message;

    private string $property;

    /**
     * @var string[]
     */
    private array $path;

    private ?int $index;

    /**
     * @var ErrorMessage[]
     */
    private array $errors;

    /**
     * @param string[] $path
     * @param ErrorMessage[] $errors
     */
    public function __construct(string $message, string $property, array $path = [], ?int $index = null, array $errors = [])
    {
        $this->message = $message;
        $this->property = $property;
        $this->path = $path;
        $this->errors = $errors;
        $this->index = $index;
    }

    public function getMessage(): string
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
     * @return ErrorMessage[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}