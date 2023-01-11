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
    private ?array $tags = null;

    /**
     * @var Response[]|null
     */
    private ?array $responses = null;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function build(): array
    {
        return [
            $this->name => $this->join(
                $this->prop('summary', $this->summary),
                $this->prop('tags', $this->tags),
                $this->prop('responses', $this->buildResponses()),
            ),
        ];
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

    public function response(int $status): Response
    {
        $response = $this->findResponse($status);
        if ($response === null) {
            $response = new Response($status);
            $this->addResponse($response);
        }

        return $response;
    }

    public function addResponse(Response $response): self
    {
        if ($this->responses === null) {
            $this->responses = [];
        }

        if ($this->findResponse($response->getStatus()) !== null) {
            throw new \LogicException(sprintf('Response with status %d already exists.', $response->getStatus()));
        }

        $this->responses[] = $response;

        return $this;
    }

    protected function findResponse(int $status): ?Response
    {
        if ($this->responses !== null) {
            foreach ($this->responses as $response) {
                if ($response->getStatus() === $status) {
                    return $response;
                }
            }
        }

        return null;
    }

    protected function buildResponses(): ?array
    {
        $result = null;
        if ($this->responses !== null) {
            $result = [];
            foreach ($this->responses as $response) {
                $result = $this->join($result, $response->build());
            }
        }

        return $result;
    }
}