<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaAttributes\Attribute;

use Attribute;
use Davajlama\Schemator\Schema\Schema;
use Davajlama\Schemator\SchemaAttributes\SchemaAttribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class RequiredAll implements SchemaAttribute
{
    private bool $requiredAll;

    public function __construct(bool $requiredAll = true)
    {
        $this->requiredAll = $requiredAll;
    }

    public function apply(Schema $schema): void
    {
        $schema->requiredDefaultValue($this->requiredAll);
    }
}
