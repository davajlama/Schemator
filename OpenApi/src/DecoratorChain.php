<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi;

use Davajlama\Schemator\OpenApi\Api\Method;
use Davajlama\Schemator\OpenApi\Api\Path;

use function array_pop;

class DecoratorChain extends Decorator
{
    /**
     * @var Decorator[]
     */
    private array $decorators = [];

    public function pushDecorator(Decorator $decorator): void
    {
        $this->decorators[] = $decorator;
    }

    public function popDecorator(): void
    {
        array_pop($this->decorators);
    }

    public function decorateMethod(Method $method): Method
    {
        foreach ($this->decorators as $decorator) {
            foreach ($decorator->methodDecorators as $methodDecorator) {
                $methodDecorator($method);
            }
        }

        return $method;
    }

    public function decoratePath(Path $path): Path
    {
        foreach ($this->decorators as $decorator) {
            foreach ($decorator->pathDecorators as $pathDecorator) {
                $pathDecorator($path);
            }
        }

        return $path;
    }
}
