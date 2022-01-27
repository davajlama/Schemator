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

    /**
     * @param string[] $path
     */
    public function __construct(string $message, string $property, array $path = [])
    {
        $this->message = $message;
        $this->property = $property;
        $this->path = $path;
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
}