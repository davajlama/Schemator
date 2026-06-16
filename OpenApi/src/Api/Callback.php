<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi\Api;

use Davajlama\Schemator\OpenApi\DecoratorChain;
use Davajlama\Schemator\OpenApi\DefinitionInterface;
use Davajlama\Schemator\OpenApi\PropertyHelper;

/**
 * OpenAPI 3.0 Callback Object.
 *
 * A callback is a named map of runtime expressions (e.g. `{$request.body#/url}`)
 * to Path Item Objects describing the out-of-band requests the API may initiate.
 * Each expression therefore reuses {@see Path}, which already models a path item,
 * so operations defined here resolve schema references into components exactly
 * like regular endpoints.
 */
final class Callback implements DefinitionInterface
{
    use PropertyHelper;

    private string $name;

    private DecoratorChain $decorator;

    /**
     * @var Path[]|null keyed internally by runtime expression
     */
    private ?array $expressions = null;

    public function __construct(string $name, DecoratorChain $decorator)
    {
        $this->name = $name;
        $this->decorator = $decorator;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function expression(string $expression): Path
    {
        $path = $this->findExpression($expression);
        if ($path === null) {
            $path = new Path($expression, $this->decorator);
            $this->addExpression($path);
        }

        return $path;
    }

    public function addExpression(Path $path): self
    {
        if ($this->expressions === null) {
            $this->expressions = [];
        }

        $this->expressions[] = $path;

        return $this;
    }

    /**
     * @return mixed[]
     */
    public function build(): array
    {
        $items = [];
        foreach ($this->expressions ?? [] as $path) {
            $items = $this->join($items, $path->build());
        }

        return [
            $this->name => $items,
        ];
    }

    private function findExpression(string $expression): ?Path
    {
        foreach ($this->expressions ?? [] as $path) {
            if ($path->getName() === $expression) {
                return $path;
            }
        }

        return null;
    }
}
