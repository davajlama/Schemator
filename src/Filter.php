<?php

declare(strict_types=1);

namespace Davajlama\Schemator;

use Davajlama\Schemator\Extractor\ArrayExtractor;
use Davajlama\Schemator\Extractor\Extractor;
use Davajlama\Schemator\Filters\FilterProperties;
use Davajlama\Schemator\Filters\FilterProperty;
use Davajlama\Schemator\Filters\FiltersFactory;
use Davajlama\Schemator\Filters\ReferencedFilterProperty;

class Filter
{
    private FiltersFactory $filtersFactory;

    private Extractor $extractor;

    /** @var FilterProperty[] */
    private array $properties = [];

    public function __construct()
    {
        $this->filtersFactory = new FiltersFactory();
        $this->extractor = new ArrayExtractor();
    }

    public function property(string $name): FilterProperty
    {
        if(!array_key_exists($name, $this->properties)) {
            $this->properties[$name] = new FilterProperty($this->filtersFactory);
        }

        return $this->properties[$name];
    }

    public function properties(array $properties): FilterProperties
    {
        return new FilterProperties($properties, $this->filtersFactory, $this);
    }

    /**
     * @return mixed
     */
    public function apply($payload)
    {
        $payload = (array) $payload;

        foreach(array_keys($payload) as $property) {
            if(array_key_exists($property, $this->properties)) {
                $prop = $this->property($property);
                $val = $payload[$property];

                if($prop instanceof ReferencedFilterProperty) {
                    $val = $prop->apply($val);
                } else {
                    $filters = $prop->getFilters();
                    foreach($filters as $filter) {
                        $val = $filter->filter($payload, $property, $val);
                    }
                }

                $payload[$property] = $val;
            }
        }

        foreach($this->properties as $name => $property) {
            if($property->isDefaultValueUsed() && !array_key_exists($name, $payload)) {
                $payload[$name] = $property->getDefaultValue();
            }
        }

        return $payload;
    }
}