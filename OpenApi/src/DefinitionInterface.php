<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi;

interface DefinitionInterface
{
    /**
     * @return mixed[]
     */
    public function build(): array;
}
