<?php

declare(strict_types=1);

namespace Davajlama\Schemator;

interface RuleInterface
{
    public function validate(mixed $data, string $property): void;
}
