<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi\Api;

use Davajlama\Schemator\OpenApi\DefinitionInterface;
use Davajlama\Schemator\OpenApi\PropertyHelper;
use Davajlama\Schemator\OpenApi\SchemaReference;
use Davajlama\Schemator\Schema\Schema;

use function count;

class Content implements DefinitionInterface
{
    use PropertyHelper;

    private string $type;

    private ?SchemaReference $schema = null;

    /**
     * @var array<int, array<string, SchemaReference>>
     */
    private ?array $oneOf = null;

    /**
     * @var mixed[]|null
     */
    private ?array $examples = null;

    /**
     * @var mixed[]|null
     */
    private ?array $example = null;

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
                $this->prop('examples', $this->examples),
                $this->prop('example', $this->example),
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

    /**
     * @param mixed[] $data
     */
    public function setExample(array $data): self
    {
        $this->examples = $data;

        return $this;
    }

    public function addExample(Example $example): self
    {
        $examples = $this->examples ?? [];

        $name = 'Example ' . (count($examples) + 1);

        $data = [
            'value' => $example->getData(),
        ];

        if ($example->getSummary() !== null) {
            $data['summary'] = $example->getSummary();
        }

        $examples[$name] = $data;

        $this->examples = $examples;

        return $this;
    }
}
