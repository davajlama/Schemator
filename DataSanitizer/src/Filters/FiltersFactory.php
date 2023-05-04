<?php

declare(strict_types=1);

namespace Davajlama\Schemator\DataSanitizer\Filters;

use Davajlama\Schemator\DataSanitizer\FilterInterface;

use function class_exists;
use function ucfirst;

final class FiltersFactory
{
    /**
     * @param mixed[] $arguments
     */
    public function create(string $name, array $arguments): ?FilterInterface
    {
        $class = 'Davajlama\Schemator\DataSanitizer\Filters\\' . ucfirst($name);
        if (class_exists($class)) {
            /** @var FilterInterface $filter */
            $filter = new $class(...$arguments);

            return $filter;
        }

        return null;
    }
}
