<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaAttributes;

use Davajlama\Schemator\Schema\Schema;

interface SchemaAttribute
{
    public function apply(Schema $schema): void;
}
