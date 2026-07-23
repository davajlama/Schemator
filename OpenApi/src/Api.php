<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi;

use Davajlama\Schemator\OpenApi\Api\ExternalDocs;
use Davajlama\Schemator\OpenApi\Api\Info;
use Davajlama\Schemator\OpenApi\Api\Method;
use Davajlama\Schemator\OpenApi\Api\Path;
use Davajlama\Schemator\OpenApi\Api\SecurityScheme;
use Davajlama\Schemator\OpenApi\Api\Server;
use Davajlama\Schemator\OpenApi\Api\Tag;
use LogicException;

use function array_key_exists;
use function sprintf;

class Api implements DefinitionInterface
{
    use PropertyHelper;

    private DecoratorChain $decorator;

    private string $version;

    private ?Info $info = null;

    private ?ExternalDocs $externalDocs = null;

    /**
     * @var Server[]|null
     */
    private ?array $servers = null;

    /**
     * @var Tag[]|null
     */
    private ?array $tags = null;

    /**
     * @var array<string, SecurityScheme>|null
     */
    private ?array $securitySchemes = null;

    /**
     * @var array<int, array<string, string[]>>|null
     */
    private ?array $security = null;

    /**
     * @var Path[]|null
     */
    private ?array $paths = null;

    public function __construct(string $version = '3.0.2')
    {
        $this->version = $version;
        $this->decorator = new DecoratorChain();
    }

    public function getDecorator(): DecoratorChain
    {
        return $this->decorator;
    }

    /**
     * @return mixed[]
     */
    public function build(): array
    {
        return $this->join(
            $this->prop('openapi', $this->version),
            $this->prop('info', $this->info?->build()),
            $this->prop('externalDocs', $this->externalDocs?->build()),
            $this->prop('servers', $this->buildServers()),
            $this->prop('tags', $this->buildTags()),
            $this->prop('security', $this->security),
            $this->prop('paths', $this->buildPaths()),
            $this->prop('components', $this->buildComponents()),
        );
    }

    public function info(): Info
    {
        if ($this->info === null) {
            $this->info = new Info();
        }

        return $this->info;
    }

    public function externalDocs(string $url): ExternalDocs
    {
        if ($this->externalDocs === null) {
            $this->externalDocs = new ExternalDocs($url);
        }

        return $this->externalDocs->url($url);
    }

    public function server(string $url): Server
    {
        $server = $this->findServer($url);
        if ($server === null) {
            $server = new Server($url);
            $this->addServer($server);
        }

        return $server;
    }

    public function addServer(Server $server): self
    {
        if ($this->findServer($server->getUrl()) !== null) {
            throw new LogicException(sprintf('Server [%s] already exists.', $server->getUrl()));
        }

        if ($this->servers === null) {
            $this->servers = [];
        }

        $this->servers[] = $server;

        return $this;
    }

    public function tag(string $name): Tag
    {
        $tag = $this->findTag($name);
        if ($tag === null) {
            $tag = new Tag($name);
            $this->addTag($tag);
        }

        return $tag;
    }

    public function addTag(Tag $tag): self
    {
        if ($this->findTag($tag->getName()) !== null) {
            throw new LogicException(sprintf('Tag [%s] already exists.', $tag->getName()));
        }

        if ($this->tags === null) {
            $this->tags = [];
        }

        $this->tags[] = $tag;

        return $this;
    }

    public function securityScheme(string $name, SecurityScheme $securityScheme): self
    {
        if ($this->securitySchemes !== null && array_key_exists($name, $this->securitySchemes)) {
            throw new LogicException(sprintf('Security scheme [%s] already exists.', $name));
        }

        if ($this->securitySchemes === null) {
            $this->securitySchemes = [];
        }

        $this->securitySchemes[$name] = $securityScheme;

        return $this;
    }

    public function security(string $name, string ...$scopes): self
    {
        if ($this->security === null) {
            $this->security = [];
        }

        $this->security[] = [$name => $scopes];

        return $this;
    }

    public function get(string $path): Method
    {
        return $this->path($path)->get();
    }

    public function post(string $path): Method
    {
        return $this->path($path)->post();
    }

    public function put(string $path): Method
    {
        return $this->path($path)->put();
    }

    public function delete(string $path): Method
    {
        return $this->path($path)->delete();
    }

    public function patch(string $path): Method
    {
        return $this->path($path)->patch();
    }

    public function path(string $name): Path
    {
        $path = $this->findPath($name);
        if ($path === null) {
            $path = $this->decorator->decoratePath(new Path($name, $this->decorator));
            $this->addPath($path);
        }

        return $path;
    }

    public function addPath(Path $path): self
    {
        if ($this->paths === null) {
            $this->paths = [];
        }

        $this->paths[] = $path;

        return $this;
    }

    protected function findServer(string $url): ?Server
    {
        if ($this->servers !== null) {
            foreach ($this->servers as $server) {
                if ($server->getUrl() === $url) {
                    return $server;
                }
            }
        }

        return null;
    }

    protected function findTag(string $name): ?Tag
    {
        if ($this->tags !== null) {
            foreach ($this->tags as $tag) {
                if ($tag->getName() === $name) {
                    return $tag;
                }
            }
        }

        return null;
    }

    /**
     * @return mixed[]|null
     */
    protected function buildServers(): ?array
    {
        $result = null;

        if ($this->servers !== null) {
            $result = [];
            foreach ($this->servers as $server) {
                $result[] = $server->build();
            }
        }

        return $result;
    }

    /**
     * @return mixed[]|null
     */
    protected function buildTags(): ?array
    {
        $result = null;

        if ($this->tags !== null) {
            $result = [];
            foreach ($this->tags as $tag) {
                $result[] = $tag->build();
            }
        }

        return $result;
    }

    /**
     * @return mixed[]|null
     */
    protected function buildComponents(): ?array
    {
        $result = null;

        if ($this->securitySchemes !== null) {
            $securitySchemes = [];
            foreach ($this->securitySchemes as $name => $securityScheme) {
                $securitySchemes[$name] = $securityScheme->build();
            }

            $result = ['securitySchemes' => $securitySchemes];
        }

        return $result;
    }

    protected function findPath(string $name): ?Path
    {
        if ($this->paths !== null) {
            foreach ($this->paths as $path) {
                if ($path->getName() === $name) {
                    return $path;
                }
            }
        }

        return null;
    }

    /**
     * @return mixed[]|null
     */
    protected function buildPaths(): ?array
    {
        $result = null;

        if ($this->paths !== null) {
            $result = [];
            foreach ($this->paths as $path) {
                $result = $this->join($result, $path->build());
            }
        }

        return $result;
    }
}
