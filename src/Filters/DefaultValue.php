<?php

declare(strict_types=1);


namespace Davajlama\Schemator\Filters;

class DefaultValue extends BaseFilter
{
    /** @var mixed */
    private $defaultValue;

    /**
     * @param mixed $defaultValue
     */
    public function __construct($defaultValue)
    {
        $this->defaultValue = $defaultValue;
    }

    public function filterValue($value)
    {

    }

}