<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi;

use Davajlama\Schemator\OpenApi\Api\Method;
use Davajlama\Schemator\OpenApi\Api\Path;

/**
 * @phpstan-type PathDecorator callable(Path): void
 * @phpstan-type MethodDecorator callable(Method): void
 */
class Decorator
{
    /**
     * @var PathDecorator[]
     */
    protected array $pathDecorators = [];

    /**
     * @var MethodDecorator[]
     */
    protected array $methodDecorators = [];

    /**
     * @param PathDecorator $callable
     */
    public function path(callable $callable): void
    {
        $this->pathDecorators[] = $callable;
    }

    /**
     * @param MethodDecorator $callable
     */
    public function method(callable $callable): void
    {
        $this->methodDecorators[] = $callable;
    }
}
