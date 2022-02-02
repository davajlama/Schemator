<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Extractor;

class ArrayExtractor implements Extractor
{
    public function extract($data, string $property)
    {
        if(!is_array($data)) {
            throw new \InvalidArgumentException('Data must ba an array, %s given.');
        }

        if(!array_key_exists($property, $data)) {
            throw new \InvalidArgumentException('Missing property: ' . $property);
        }

        return $data[$property];
    }
}