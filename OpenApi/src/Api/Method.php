<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi\Api;

use Davajlama\Schemator\OpenApi\DefinitionInterface;
use Davajlama\Schemator\OpenApi\PropertyHelper;

class Method implements DefinitionInterface
{
    use PropertyHelper;

    const GET = 'get';
    const POST = 'post';
    const PUT = 'put';
    const PATCH = 'patch';

    private string $name;

    private ?string $summary;

    /**
     * @var string[]
     */
    private ?array $tags;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function summary(string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function tags(string ...$tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function build(): array
    {
        return [
            $this->name => $this->join(
                $this->prop('summary', $this->summary),
                $this->prop('tags', $this->tags),
            ),
        ];
    }
}