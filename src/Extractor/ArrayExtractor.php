<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Extractor;

use Davajlama\Schemator\Exception\PropertyNotExistsException;
use InvalidArgumentException;

use function array_key_exists;
use function gettype;
use function is_array;
use function sprintf;

class ArrayExtractor implements ExtractorInterface
{
    public function extract(mixed $data, string $property): mixed
    {
        if (!is_array($data)) {
            throw new InvalidArgumentException(sprintf('Data must ba an array, %s given.', gettype($data)));
        }

        if (!array_key_exists($property, $data)) {
            throw new PropertyNotExistsException(sprintf('Property %s not exists.', $property));
        }

        return $data[$property];
    }
}
