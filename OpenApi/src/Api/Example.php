<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi\Api;

class Example
{
    /**
     * @var mixed[]
     */
    private array $data;

    private string|null $summary = null;

    /**
     * @param mixed[] $data
     */
    public function __construct(array $data, ?string $summary = null)
    {
        $this->data = $data;
        $this->summary = $summary;
    }

    /**
     * @return mixed[]
     */
    public function getData(): array
    {
        return $this->data;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }
}
