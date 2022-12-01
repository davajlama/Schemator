<?php

declare(strict_types=1);

namespace Davajlama\JsonSchemaGenerator;

use function array_map;
use function count;

class Definition
{
    /**
     * @var string[]|null
     */
    private ?array $type = null;

    private ?string $title = null;

    private ?string $description = null;

    private ?bool $additionalProperties = null;

    /**
     * @var array<string, Definition>|null
     */
    private ?array $properties = null;

    private ?array $items = null;

    private ?array $enum = null;

    private float|int|null $minimum = null;

    private float|int|null $maximum = null;

    /**
     * @var string[]
     */
    private ?array $required = null;

    /**
     * @var mixed[]|null
     */
    private ?array $examples = null;

    /**
     * @return array<string, mixed>
     */
    public function build(): array
    {
        return $this->join(
            $this->prop('type', $this->buildType()),
            $this->prop('title', $this->title),
            $this->prop('description', $this->description),
            $this->prop('additionalProperties', $this->additionalProperties),
            $this->prop('required', $this->required),
            $this->prop('properties', $this->buildProperties()),
            $this->prop('items', $this->items),
            $this->prop('enum', $this->enum),
            $this->prop('minimum', $this->minimum),
            $this->prop('maximum', $this->maximum),
            $this->prop('examples', $this->examples),
        );
    }

    /**
     * @return array<string, mixed>
     */
    protected function buildProperties(): ?array
    {
        $result = null;
        if ($this->properties !== null) {
            $result = array_map(static fn(Definition $def) => $def->build(), $this->properties);
        }

        return $result;
    }

    /**
     * @return string[]|string|null
     */
    protected function buildType(): array|string|null
    {
        $result = null;
        if ($this->type !== null) {
            if (count($this->type) === 1) {
                $result = $this->type[0];
            } else {
                $result = $this->type;
            }
        }

        return $result;
    }

    public function addType(string $type): self
    {
        if ($this->type === null) {
            $this->type = [];
        }

        $this->type[] = $type;

        return $this;
    }

    public function addProperty(string $name, Definition $definition, bool $required): self
    {
        if ($this->properties === null) {
            $this->properties = [];
        }

        if ($required) {
            if ($this->required === null) {
                $this->required = [];
            }

            $this->required[] = $name;
        }

        $this->properties[$name] = $definition;

        return $this;
    }

    public function setTitle(string $title): Definition
    {
        $this->title = $title;

        return $this;
    }

    public function setDescription(string $description): Definition
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param mixed[] $examples
     */
    public function setExamples(array $examples): Definition
    {
        $this->examples = $examples;

        return $this;
    }

    public function setAdditionalProperties(bool $additionalProperties): Definition
    {
        $this->additionalProperties = $additionalProperties;

        return $this;
    }

    public function setItems(array $definition): Definition
    {
        $this->items = $definition;

        return $this;
    }

    public function setEnum(array $values): Definition
    {
        $this->enum = $values;

        return $this;
    }

    public function setMinimum(float|int $minimum): Definition
    {
        $this->minimum = $minimum;

        return $this;
    }

    public function setMaximum(float|int $maximum): Definition
    {
        $this->maximum = $maximum;

        return $this;
    }

    /**
     * @param string|int|float|bool|mixed[]|null $value
     * @return array<string, string|int|float|bool|mixed[]>
     */
    protected function prop(string $key, string|int|float|bool|array|null $value): array
    {
        $result = [];
        if ($value !== null) {
            $result[$key] = $value;
        }

        return $result;
    }

    /**
     * @param array<string, mixed> ...$arrays
     * @return array<string, mixed>
     */
    protected function join(array ...$arrays): array
    {
        $joined = [];
        foreach ($arrays as $array) {
            foreach ($array as $key => $value) {
                $joined[$key] = $value;
            }
        }

        return $joined;
    }
}