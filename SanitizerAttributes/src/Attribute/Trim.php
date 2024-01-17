<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SanitizerAttributes\Attribute;

use Attribute;
use Davajlama\Schemator\DataSanitizer\PropertiesGroup;
use Davajlama\Schemator\SanitizerAttributes\GroupAttribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_CLASS)]
final class Trim implements GroupAttribute
{
    private string $characters;

    public function __construct(string $characters = " \t\n\r\0\x0B")
    {
        $this->characters = $characters;
    }

    public function apply(PropertiesGroup $group): void
    {
        $group->trim($this->characters);
    }
}
