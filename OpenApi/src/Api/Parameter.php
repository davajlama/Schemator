<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi\Api;

use Davajlama\Schemator\OpenApi\DefinitionInterface;
use Davajlama\Schemator\OpenApi\PropertyHelper;
use Davajlama\Schemator\OpenApi\SchemaReference;
use Davajlama\Schemator\Schema\Property;
use Davajlama\Schemator\Schema\Schema;

final class Parameter implements DefinitionInterface
{
    use PropertyHelper;

    private string $name;

    private ?string $description = null;

    private ?string $in = null;

    private bool $required = false;

    private ?SchemaReference $schema = null;

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
            $this->prop('description', $this->description),
            $this->prop('in', $this->in),
            $this->prop('required', $this->required),
            $this->prop('schema', $this->schema?->getSchema() instanceof Property ? $this->schema : $this->join(
                $this->prop('$ref', $this->schema),
            )),
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function required(bool $required = true): self
    {
        $this->required = $required;

        return $this;
    }

    public function schema(Schema|Property|string $schema): self
    {
        $this->schema = new SchemaReference($schema);

        return $this;
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

    public function inHeader(): self
    {
        $this->in('header');

        return $this;
    }

    public function inPath(): self
    {
        $this->in('path');

        return $this;
    }
}
