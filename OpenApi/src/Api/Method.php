<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi\Api;

use Davajlama\Schemator\OpenApi\DefinitionInterface;
use Davajlama\Schemator\OpenApi\PropertyHelper;
use Davajlama\Schemator\Schema\Schema;
use LogicException;

use function sprintf;

class Method implements DefinitionInterface
{
    use PropertyHelper;

    public const GET = 'get';
    public const POST = 'post';
    public const PUT = 'put';
    public const PATCH = 'patch';
    public const DELETE = 'delete';

    private string $name;

    private ?string $summary = null;

    private ?Parameters $parameters = null;

    private ?RequestBody $requestBody = null;

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

    /**
     * @return mixed[]
     */
    public function build(): array
    {
        return [
            $this->name => $this->join(
                $this->prop('summary', $this->summary),
                $this->prop('tags', $this->tags),
                $this->prop('parameters', $this->parameters?->buildParameters()),
                $this->prop('requestBody', $this->requestBody?->build()),
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

    public function requestBody(): RequestBody
    {
        if ($this->requestBody === null) {
            $this->requestBody = new RequestBody();
        }

        return $this->requestBody;
    }

    public function jsonRequestBody(Schema $schema): Content
    {
        return $this->requestBody()->content('application/json')->schema($schema);
    }

    public function queryParam(string $name, bool $required = false): Parameter
    {
        return $this->parameters()->param($name)->required($required)->inQuery();
    }

    public function parameters(): Parameters
    {
        if ($this->parameters === null) {
            $this->parameters = new Parameters();
        }

        return $this->parameters;
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

    public function jsonResponse200Ok(Schema $schema): Response
    {
        return $this->response200Ok()->json($schema);
    }

    public function response200Ok(): Response
    {
        $response = $this->findResponse(200);
        if ($response === null) {
            $response = new Response(200);
            $response->description('Ok.');

            $this->addResponse($response);
        }

        return $response;
    }

    public function response204NoContent(): Response
    {
        $response = $this->findResponse(204);
        if ($response === null) {
            $response = new Response(204);
            $response->description('Ok. No content.');

            $this->addResponse($response);
        }

        return $response;
    }

    public function response400BadRequest(): Response
    {
        $response = $this->findResponse(400);
        if ($response === null) {
            $response = new Response(400);
            $response->description('Bad request.');

            $this->addResponse($response);
        }

        return $response;
    }

    public function response401AuthorizationRequired(): Response
    {
        $response = $this->findResponse(401);
        if ($response === null) {
            $response = new Response(401);
            $response->description('Authorization required.');

            $this->addResponse($response);
        }

        return $response;
    }

    public function response403PermissionDenied(): Response
    {
        $response = $this->findResponse(403);
        if ($response === null) {
            $response = new Response(403);
            $response->description('Permission denied.');

            $this->addResponse($response);
        }

        return $response;
    }

    public function response404ResourceNotFound(): Response
    {
        $response = $this->findResponse(404);
        if ($response === null) {
            $response = new Response(404);
            $response->description('Resource not found.');

            $this->addResponse($response);
        }

        return $response;
    }

    public function response500InternalServerError(): Response
    {
        $response = $this->findResponse(500);
        if ($response === null) {
            $response = new Response(500);
            $response->description('Internal server error.');

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
            throw new LogicException(sprintf('Response with status %d already exists.', $response->getStatus()));
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

    /**
     * @return mixed[]|null
     */
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
