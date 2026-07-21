<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi;

use Davajlama\Schemator\Schema\Schema;

interface ComponentNameResolverInterface
{
    public function support(Schema|string $schema): bool;

    public function resolve(Schema|string $schema): string;
}
