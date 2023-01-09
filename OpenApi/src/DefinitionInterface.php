<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi;

interface DefinitionInterface
{
    public function build(): array;
}