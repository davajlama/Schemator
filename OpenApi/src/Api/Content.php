<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi\Api;

use Davajlama\Schemator\OpenApi\DefinitionInterface;
use Davajlama\Schemator\OpenApi\PropertyHelper;
use Davajlama\Schemator\OpenApi\SchemaReference;
use Davajlama\Schemator\Schema\Schema;

class Content implements DefinitionInterface
{
    use PropertyHelper;

    private string $type;

    private ?SchemaReference $schema = null;

    /**
     * @var array<int, array<string, SchemaReference>>
     */
    private ?array $oneOf = null;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return mixed[]
     */
    public function build(): array
    {
        return [
            $this->type => $this->join(
                $this->prop('schema', $this->join(
                    $this->prop('$ref', $this->schema),
                    $this->prop('oneOf', $this->oneOf),
                )),
            ),
        ];
    }

    public function schema(Schema|string $schema): self
    {
        $this->schema = new SchemaReference($schema);

        return $this;
    }

    public function oneOf(Schema|string ...$schemas): self
    {
        $list = [];
        foreach ($schemas as $schema) {
            $list[] = ['$ref' => new SchemaReference($schema)];
        }

        $this->oneOf = $list;

        return $this;
    }
}
