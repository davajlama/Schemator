<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaAttributes\Attribute;

use Davajlama\Schemator\Schema\Schema;
use Davajlama\Schemator\SchemaAttributes\EntityAttribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class AdditionalProperties implements EntityAttribute
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