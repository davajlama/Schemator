<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Filters;

interface Filter
{
    /**
     * @param mixed $data
     * @param mixed $value
     * @return mixed
     */
    public function filter($data, string $property, $value);
}