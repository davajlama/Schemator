<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaAttributes\Attribute;

use Attribute;
use Davajlama\Schemator\Schema\Schema;
use Davajlama\Schemator\SchemaAttributes\SchemaAttribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class AdditionalProperties implements SchemaAttribute
{
    private bool $allowed;

    public function __construct(bool $allowed)
    {
        $this->allowed = $allowed;
    }

    public function apply(Schema $schema): void
    {
        $schema->additionalProperties($this->allowed);
    }
}
