<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi\Api;

use Davajlama\Schemator\OpenApi\DefinitionInterface;
use Davajlama\Schemator\OpenApi\PropertyHelper;

final class Path implements DefinitionInterface
{
    use PropertyHelper;

    private string $name;

    /**
     * @var Method[]|null
     */
    private ?array $methods = null;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function build(): array
    {
        return [
            $this->prop($this->name, $this->buildMethods()),
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function method(string $name): Method
    {
        $method = $this->findMethod($name);
        if ($method === null) {
            $method = new Method($name);
            $this->addMethod($method);
        }

        return $method;
    }

    public function addMethod(Method $method): self
    {
        if ($this->methods === null) {
            $this->methods = [];
        }

        if ($this->findMethod($method->getName()) !== null) {
            throw new \LogicException(sprintf('Method %s already exists.', $method->getName()));
        }

        $this->methods[] = $method;

        return $this;
    }

    protected function buildMethods(): ?array
    {
        $result = null;
        if ($this->methods !== null) {
            $result = [];
            foreach ($this->methods as $method) {
                $result = $this->join($result, $method->build());
            }
        }

        return $result;
    }

    protected function findMethod(string $name): ?Method
    {
        if ($this->methods !== null) {
            foreach ($this->methods as $method) {
                if ($method->getName() === $name) {
                    return $method;
                }
            }
        }

        return null;
    }
}