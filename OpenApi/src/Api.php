<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi;

use Davajlama\Schemator\OpenApi\Api\Info;
use Davajlama\Schemator\OpenApi\Api\Method;
use Davajlama\Schemator\OpenApi\Api\Path;

class Api implements DefinitionInterface
{
    use PropertyHelper;

    private string $version;

    private ?Info $info = null;

    /**
     * @var Path[]|null
     */
    private ?array $paths = null;

    public function __construct(string $version = '3.0.2')
    {
        $this->version = $version;
    }

    /**
     * @return mixed[]
     */
    public function build(): array
    {
        return $this->join(
            $this->prop('openapi', $this->version),
            $this->prop('info', $this->info?->build()),
            $this->prop('paths', $this->buildPaths()),
        );
    }

    public function info(): Info
    {
        if ($this->info === null) {
            $this->info = new Info();
        }

        return $this->info;
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
            $path = new Path($name);
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
