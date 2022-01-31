<?php

declare(strict_types=1);

namespace Davajlama\Schemator;

use Davajlama\Schemator\Exception\ValidationFailedException;
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
        $this->errors = $this->_validate($definition, $data, []);

        return count($this->errors) === 0;
    }

    protected function _validate(Definition $definition, $data, array $path): array
    {
        $properties = $definition->getProperties();

        $data = (array) $data;

        $unresolvedProperties = array_keys($data);

        $errors = [];
        foreach($unresolvedProperties as $unresolvedProperty) {
            if(array_key_exists($unresolvedProperty, $properties)) {
                try {
                    $property = $properties[$unresolvedProperty];

                    if($property instanceof ReferencedProperty) {
                        array_push($path, $unresolvedProperty);
                        $subErrors = $this->_validate($property->getReferencedDefinition(), $data[$unresolvedProperty], $path);

                        foreach($subErrors as $subError) {
                            $errors[] = $subError;
                        }

                        unset($unresolvedProperties[array_search($unresolvedProperty, $unresolvedProperties)]);
                        unset($properties[$unresolvedProperty]);

                        array_pop($path);
                    } else {
                        foreach($property->getRules() as $rule) {
                            if($rule instanceof ExtractorAwareInterface) {
                                $rule->setExtractor($this->extractor);
                            }

                            try {
                                $rule->validate($data, $unresolvedProperty);
                            } catch (ValidationFailedException $e) {
                                $errors[] = new ErrorMessage($e->getMessage(), $unresolvedProperty, $path, null, $e->getErrors());
                            }
                        }

                        unset($unresolvedProperties[array_search($unresolvedProperty, $unresolvedProperties)]);
                        unset($properties[$unresolvedProperty]);
                    }
                } catch (\InvalidArgumentException $e) {

                }
            }
        }

        if(!$definition->isAdditionalPropertiesAllowed() && count($unresolvedProperties) > 0) {
            $errors[] = new ErrorMessage('Additional properties not allowed.', '*', $path);
        }

        foreach($properties as $name => $property) {
            if($property->isRequired()) {
                $message = sprintf('Property [%s] is required.', $name);
                $errors[] = new ErrorMessage($message, $name, $path);
            }
        }

        return $errors;
    }

    /**
     * @return ErrorMessage[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function dumpErrors()
    {
        var_dump(MessagesFormatter::formatErrors($this->getErrors()));
    }

}