<?php

declare(strict_types=1);

namespace Davajlama\Schemator\DataSanitizer;

use Davajlama\Schemator\DataSanitizer\Filters\FiltersFactory;
use LogicException;

use function sprintf;

/**
 * @method self trim(string $characters = ' \t\n\r\0\x0B')
 * @method self replace(string $search, string $replace)
 * @method self spaceless()
 * @method self emptyStringToNull()
 * @method self numericToFloat()
 * @method self numericToInt()
 * @method self defaultIfNotExists(mixed $default)
 * @method self defaultValue(mixed $default)
 * @method self stringedNumberToInt()
 * @method self stringedNumberToFloat()
 */
class PropertiesGroup
{
    private FiltersFactory $filtersFactory;

    /**
     * @var string[]
     */
    private array $properties;

    /**
     * @var FilterInterface[]
     */
    private array $filters = [];

    /**
     * @param string[] $properties
     */
    public function __construct(array $properties, ?FiltersFactory $filtersFactory = null)
    {
        $this->properties = $properties;
        $this->filtersFactory = $filtersFactory ?? new FiltersFactory();
    }

    public function filter(FilterInterface $filter): self
    {
        $this->filters[] = $filter;

        return $this;
    }

    /**
     * @return FilterInterface[]
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * @return string[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param mixed[] $arguments
     */
    public function __call(string $name, array $arguments): self
    {
        $filter = $this->filtersFactory->create($name, $arguments);
        if ($filter === null) {
            throw new LogicException(sprintf('Filter %s not exists.', $name));
        }

        $this->filters[] = $filter;

        return $this;
    }
}
