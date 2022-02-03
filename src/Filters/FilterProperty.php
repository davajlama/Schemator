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

    /** @var mixed */
    private $defaultValue = null;

    private bool $defaultValueUsed = false;

    public function __construct(FiltersFactory $filtersFactory)
    {
        $this->filtersFactory = $filtersFactory;
    }

    public function default($value): self
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

    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    public function isDefaultValueUsed(): bool
    {
        return $this->defaultValueUsed;
    }


}