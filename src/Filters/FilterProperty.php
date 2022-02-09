<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Filters;

class FilterProperty
{
    private FiltersFactory $filtersFactory;

    /**
     * @var Filter[]
     */
    private array $filters = [];

    private mixed $defaultValue = null;

    private bool $defaultValueUsed = false;

    public function __construct(FiltersFactory $filtersFactory)
    {
        $this->filtersFactory = $filtersFactory;
    }

    public function default(mixed $value): self
    {
        $this->defaultValue = $value;
        $this->defaultValueUsed = true;

        return $this;
    }

    public function trim(): self
    {
        $filter = $this->filtersFactory->crateTrim();
        $this->addFilter($filter);

        return $this;
    }

    public function upper(): self
    {
        $filter = $this->filtersFactory->createUpper();
        $this->addFilter($filter);

        return $this;
    }

    public function addFilter(Filter $filter): self
    {
        $this->filters[] = $filter;

        return $this;
    }

    /**
     * @return Filter[]
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    public function getDefaultValue(): mixed
    {
        return $this->defaultValue;
    }

    public function isDefaultValueUsed(): bool
    {
        return $this->defaultValueUsed;
    }
}
