<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Demo\Entity;

final class Group
{
    public function __construct(
        public int|string|null $id,
    ) {
    }
}
