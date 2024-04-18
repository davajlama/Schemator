<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SanitizerAttributes\Attribute;

use Attribute;
use Davajlama\Schemator\DataSanitizer\PropertiesGroup;
use Davajlama\Schemator\SanitizerAttributes\GroupAttribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_CLASS | Attribute::TARGET_PARAMETER)]
final class NumericToFloat implements GroupAttribute
{
    public function apply(PropertiesGroup $group): void
    {
        $group->numericToFloat();
    }
}
