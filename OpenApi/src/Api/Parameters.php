<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi\Api;

use LogicException;

use function sprintf;

final class Parameters
{
    /**
     * @var Parameter[]|null
     */
    private ?array $parameters = [];

    public function param(string $name): Parameter
    {
        $param = $this->findParam($name);
        if ($param === null) {
            $param = new Parameter($name);

            $this->addParam($param);
        }

        return $param;
    }

    public function findParam(string $name): ?Parameter
    {
        if ($this->parameters !== null) {
            foreach ($this->parameters as $parameter) {
                if ($parameter->getName() === $name) {
                    return $parameter;
                }
            }
        }

        return null;
    }

    public function addParam(Parameter $parameter): self
    {
        if ($this->parameters === null) {
            $this->parameters = [];
        }

        if ($this->findParam($parameter->getName()) !== null) {
            throw new LogicException(sprintf('Parameter %s already exists.', $parameter->getName()));
        }

        $this->parameters[] = $parameter;

        return $this;
    }

    /**
     * @return mixed[]|null
     */
    public function buildParameters(): ?array
    {
        $parameters = null;
        if ($this->parameters !== null) {
            $parameters = [];
            foreach ($this->parameters as $parameter) {
                $parameters[] = $parameter->build();
            }
        }

        return $parameters;
    }
}
