<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SanitizerAttributes\Attribute;

use Attribute;
use Davajlama\Schemator\DataSanitizer\PropertiesGroup;
use Davajlama\Schemator\SanitizerAttributes\GroupAttribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
final class DefaultValue implements GroupAttribute
{
    private mixed $default;

    public function __construct(mixed $default)
    {
        $this->default = $default;
    }

    public function apply(PropertiesGroup $group): void
    {
        $group->defaultValue($this->default);
    }
}
