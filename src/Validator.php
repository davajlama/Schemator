<?php

declare(strict_types=1);

namespace Davajlama\Schemator;

use Davajlama\Schemator\Extractor\ValueExtractor;
use Davajlama\Schemator\Rules\ExtractorAwareInterface;

class Validator
{
    private ValueExtractor $extractor;

    private array $errors = [];

    public function __construct(ValueExtractor $extractor)
    {
        $this->extractor = $extractor;
    }

    public function validate(Definition $definition, $data): bool
    {
        $properties = $definition->getProperties();

        $data = (array) $data;

        $unresolvedProperties = array_keys($data);

        $errors = [];
        foreach($unresolvedProperties as $unresolvedProperty) {
            if(array_key_exists($unresolvedProperty, $properties)) {
                try {
                    $property = $properties[$unresolvedProperty];
                    foreach($property->getRules() as $rule) {
                        if($rule instanceof ExtractorAwareInterface) {
                            $rule->setExtractor($this->extractor);
                        }

                        try {
                            $rule->validate($data, $unresolvedProperty);
                        } catch (\InvalidArgumentException $e) {
                            $errors[] = $e;
                        }
                    }

                    unset($unresolvedProperties[array_search($unresolvedProperty, $unresolvedProperties)]);
                    unset($properties[$unresolvedProperty]);
                } catch (\InvalidArgumentException $e) {

                }
            }
        }

        if(!$definition->isAdditionalPropertiesAllowed() && count($unresolvedProperties) > 0) {
            $errors[] = new \InvalidArgumentException('Additional properties not allowed.');
        }

        foreach($properties as $property) {
            if($property->isRequired()) {
                $errors[] = new \InvalidArgumentException('Property is required.');
            }
        }

        //var_dump(array_map(fn($e) => $e->getMessage(), $errors));
        $this->errors = $errors;
        return count($errors) === 0;
    }

    public function dumpErrors()
    {
        var_dump(array_map(fn($e) => $e->getMessage(), $this->errors));
    }

}