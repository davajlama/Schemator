<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema;

interface RuleInterface
{
    public function validate(mixed $data, string $property): void;
}
