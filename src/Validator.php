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
        $rules = $definition->buildRules()->toArray();

        $data = (array) $data;

        $resolvedProperties = [];
        $unresolvedProperties = array_keys($data);

        $errors = [];
        foreach($unresolvedProperties as $property) {
            if(array_key_exists($property, $rules)) {
                try {

                    foreach($rules[$property] as $rule) {
                        if($rule instanceof ExtractorAwareInterface) {
                            $rule->setExtractor($this->extractor);
                        }

                        $rule->validate($data, $property);
                    }

                    unset($unresolvedProperties[$property]);
                    $resolvedProperties[] = $property;
                } catch (\InvalidArgumentException $e) {
                    $errors[] = $e;
                }
            }
        }

        $this->errors = $errors;
        return count($errors) === 0;
    }

}