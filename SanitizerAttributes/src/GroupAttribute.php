<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SanitizerAttributes;

use Davajlama\Schemator\DataSanitizer\PropertiesGroup;

interface GroupAttribute
{
    public function apply(PropertiesGroup $group): void;
}
