<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaAttributes\Attribute;

use Attribute;
use Davajlama\Schemator\Schema\Schema;
use Davajlama\Schemator\SchemaAttributes\SchemaAttribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
final class PropertyInformation implements SchemaAttribute
{
    private string $name;

    private string|null $description = null;

    private string|int|float|bool|null $example = null;

    /**
     * @var scalar[]|null
     */
    private array|null $examples = null;

    /**
     * @param scalar[]|null $examples
     */
    public function __construct(
        string $name,
        string|null $description = null,
        string|int|float|bool|null $example = null,
        array|null $examples = null,
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->example = $example;
        $this->examples = $examples;
    }

    public function apply(Schema $schema): void
    {
        $property = $schema->prop($this->name);

        if ($this->description !== null) {
            $property->description($this->description);
        }

        if ($this->example !== null) {
            $property->example($this->example);
        }

        if ($this->examples !== null) {
            $property->examples($this->examples);
        }
    }
}
