<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Extractor;

use InvalidArgumentException;

use function array_key_exists;
use function is_array;

class ArrayExtractor implements Extractor
{
    public function extract($data, string $property): mixed
    {
        if (!is_array($data)) {
            throw new InvalidArgumentException('Data must ba an array, %s given.');
        }

        if (!array_key_exists($property, $data)) {
            throw new InvalidArgumentException('Missing property: ' . $property);
        }

        return $data[$property];
    }
}
