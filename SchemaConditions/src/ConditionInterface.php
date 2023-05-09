<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaConditions;

interface ConditionInterface
{
    public function validate(mixed $payload): void;
}
