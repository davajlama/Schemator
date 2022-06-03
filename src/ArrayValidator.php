<?php

declare(strict_types=1);

namespace Davajlama\Schemator;

use Davajlama\Schemator\Exception\ValidationFailedException;
use Davajlama\Schemator\Extractor\ArrayExtractorInterface;
use Davajlama\Schemator\Extractor\ExtractorAwareInterface;
use Davajlama\Schemator\Extractor\ExtractorInterface;
use InvalidArgumentException;

use function array_key_exists;
use function array_keys;
use function array_pop;
use function array_push;
use function array_search;
use function count;
use function is_array;
use function sprintf;

class ArrayValidator
{
    private ExtractorInterface $extractor;

    public function __construct()
    {
        $this->extractor = new ArrayExtractorInterface();
    }

    /**
     * @param mixed[] $payload
     */
    public function validate(Schema $schema, array $payload): void
    {
        $errors = $this->doValidate($schema, $payload, []);

        if (count($errors) > 0) {
            throw new ValidationFailedException('Data is not valid.', $errors);
        }
    }

    /**
     * @param string[] $path
     * @param mixed[] $payload
     * @return ErrorMessage[]
     */
    protected function doValidate(Schema $schema, array $payload, array $path): array
    {
        $properties = $schema->getProperties();

        $unresolvedProperties = array_keys($payload);

        $errors = [];
        foreach ($unresolvedProperties as $unresolvedProperty) {
            if (array_key_exists($unresolvedProperty, $properties)) {
                try {
                    $property = $properties[$unresolvedProperty];

                    if ($property->getReference() !== null) {
                        array_push($path, $unresolvedProperty);

                        $subErrors = [];
                        if (is_array($payload[$unresolvedProperty])) {
                            $subErrors = $this->doValidate($property->getReference(), $payload[$unresolvedProperty], $path);
                        } else {
                            $subErrors[] = new ErrorMessage('Must be an array.', $unresolvedProperty, $path);
                        }


                        foreach ($subErrors as $subError) {
                            $errors[] = $subError;
                        }

                        unset($unresolvedProperties[array_search($unresolvedProperty, $unresolvedProperties, true)]);
                        unset($properties[$unresolvedProperty]);

                        array_pop($path);
                    } else {
                        foreach ($property->getRules() as $rule) {
                            if ($rule instanceof ExtractorAwareInterface) {
                                $rule->setExtractor($this->extractor);
                            }

                            try {
                                $rule->validate($payload, $unresolvedProperty);
                            } catch (ValidationFailedException $e) {
                                $errors[] = new ErrorMessage($e->getMessage(), $unresolvedProperty, $path, null, $e->getErrors());
                            }
                        }

                        unset($unresolvedProperties[array_search($unresolvedProperty, $unresolvedProperties, true)]);
                        unset($properties[$unresolvedProperty]);
                    }
                } catch (InvalidArgumentException $e) {
                }
            }
        }

        if (!$schema->isAdditionalPropertiesAllowed() && count($unresolvedProperties) > 0) {
            $errors[] = new ErrorMessage('Additional properties not allowed.', '*', $path);
        }

        foreach ($properties as $name => $property) {
            if ($property->isRequired()) {
                $message = sprintf('Property [%s] is required.', $name);
                $errors[] = new ErrorMessage($message, $name, $path);
            }
        }

        return $errors;
    }
}