<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi\Api;

use Davajlama\Schemator\OpenApi\DefinitionInterface;
use Davajlama\Schemator\OpenApi\PropertyHelper;

final class Parameter implements DefinitionInterface
{
    use PropertyHelper;

    private string $name;

    private ?string $in = null;

    private bool $required = true;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed[]
     */
    public function build(): array
    {
        return $this->join(
            $this->prop('name', $this->name),
            $this->prop('in', $this->in),
            $this->prop('required', $this->required),
        );
    }

    public function in(string $where): self
    {
        $this->in = $where;

        return $this;
    }

    public function inQuery(): self
    {
        $this->in('query');

        return $this;
    }
}
