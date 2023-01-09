<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi;

use Davajlama\Schemator\OpenApi\Api\Info;
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

    public function build(): array
    {
        return $this->join(
            $this->prop('version', $this->version),
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