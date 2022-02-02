<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Filters;

class FilterProperties extends FilterProperty
{
    /** @var string[] */
    private array $properties;

    private FiltersFactory $filtersFactory;

    private \Davajlama\Schemator\Filter $filter;

    /**
     * @param string[] $properties
     * @param FiltersFactory $filtersFactory
     */
    public function __construct(array $properties, FiltersFactory $filtersFactory, \Davajlama\Schemator\Filter $filter)
    {
        parent::__construct($filtersFactory);

        $this->properties = $properties;
        $this->filter = $filter;
    }

    public function addFilter(Filter $filter): FilterProperty
    {
        foreach($this->properties as $property) {
            $this->filter->property($property)->addFilter($filter);
        }

        return $this;
    }
}