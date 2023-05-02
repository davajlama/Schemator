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

    public function jsonRequestBody(Schema|string $schema): Content
    {
        return $this->requestBody()->content('application/json')->schema($schema);
    }

    public function queryParam(string $name, bool $required = false): Parameter
    {
        return $this->parameters()->param($name)->required($required)->inQuery();
    }

    public function headerParam(string $name, bool $required = false): Parameter
    {
        return $this->parameters()->param($name)->required($required)->inHeader();
    }

    public function pathParam(string $name, bool $required = false): Parameter
    {
        return $this->parameters()->param($name)->required($required)->inPath();
    }

    public function parameters(): Parameters
    {
        if ($this->parameters === null) {
            $this->parameters = new Parameters();
        }

        return $this->parameters;
    }

    public function jsonResponse200Ok(Schema|string $schema): Response
    {
        return $this->response200Ok()->json($schema);
    }

    public function jsonResponse400BadRequest(Schema|string $schema): Response
    {
        return $this->response400BadRequest()->json($schema);
    }

    public function jsonResponse401AuthorizationRequired(Schema|string $schema): Response
    {
        return $this->response401AuthorizationRequired()->json($schema);
    }

    public function jsonResponse403PermissionDenied(Schema|string $schema): Response
    {
        return $this->response403PermissionDenied()->json($schema);
    }

    public function jsonResponse404ResourceNotFound(Schema|string $schema): Response
    {
        return $this->response404ResourceNotFound()->json($schema);
    }

    public function jsonResponse409Conflict(Schema|string $schema): Response
    {
        return $this->response409Conflict()->json($schema);
    }

    public function jsonResponse500InternalServerError(Schema|string $schema): Response
    {
        return $this->response500InternalServerError()->json($schema);
    }

    public function response200Ok(): Response
    {
        return $this->response(200, 'Ok.');
    }

    public function response204NoContent(): Response
    {
        return $this->response(204, 'Ok. No content.');
    }

    public function response400BadRequest(): Response
    {
        return $this->response(400, 'Bad request.');
    }

    public function response401AuthorizationRequired(): Response
    {
        return $this->response(401, 'Authorization required.');
    }

    public function response403PermissionDenied(): Response
    {
        return $this->response(403, 'Permission denied.');
    }

    public function response404ResourceNotFound(): Response
    {
        return $this->response(404, 'Resource not found.');
    }

    public function response409Conflict(): Response
    {
        return $this->response(409, 'Conflict.');
    }

    public function response500InternalServerError(): Response
    {
        return $this->response(500, 'Internal server error.');
    }

    public function response(int $status, ?string $description): Response
    {
        $response = $this->findResponse($status);
        if ($response === null) {
            $response = new Response($status);
            $this->addResponse($response);

            if ($description !== null) {
                $response->description($description);
            }
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
