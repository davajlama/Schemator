<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Filters;

use RuntimeException;

class ReferencedFilterProperty extends FilterProperty
{
    private \Davajlama\Schemator\Filter $definition;

    public function __construct(FiltersFactory $filtersFactory, \Davajlama\Schemator\Filter $definition)
    {
        parent::__construct($filtersFactory);

        $this->definition = $definition;
    }

    public function addFilter(Filter $filter): FilterProperty
    {
        throw new RuntimeException('Cannot use in referenced filter property.');
    }

    public function apply($payload)
    {
        return $this->definition->apply($payload);
    }
}
