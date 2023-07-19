<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Validator;

use function strtr;

final class Message
{
    private string $description;

    /**
     * @var array<string, int|float|string>
     */
    private array $params;

    /**
     * @param array<string, int|float|string> $params
     */
    public function __construct(string $description, array $params = [])
    {
        $this->description = $description;
        $this->params = $params;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return array<string, int|float|string>
     */
    public function getParams(): array
    {
        return $this->params;
    }

    public function toString(): string
    {
        return strtr($this->description, $this->params);
    }
}
