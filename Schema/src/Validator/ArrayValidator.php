<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Validator;

use Davajlama\Schemator\Exception\ValidationFailedException;
use Davajlama\Schemator\Extractor\ArrayExtractor;
use Davajlama\Schemator\Extractor\ExtractorAwareInterface;
use Davajlama\Schemator\Extractor\ExtractorInterface;
use Davajlama\Schemator\Schema;
use Davajlama\Schemator\SchemaFactoryHelper;
use InvalidArgumentException;

use function array_key_exists;
use function array_keys;
use function array_pop;
use function array_push;
use function array_search;
use function count;
use function is_array;
use function sprintf;

class ArrayValidator implements ValidatorInterface
{
    use SchemaFactoryHelper;

    private ExtractorInterface $extractor;

    public function __construct()
    {
        $this->extractor = new ArrayExtractor();
    }

    /**
     * @param mixed[] $payload
     */
    public function validate(Schema|string $schema, array $payload): void
    {
        $schema = $this->createSchema($schema);
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

                        if (!$property->isNullable() || $payload[$unresolvedProperty] !== null) {
                            $subErrors = [];
                            if (is_array($payload[$unresolvedProperty])) {
                                $subErrors = $this->doValidate($property->getReference(), $payload[$unresolvedProperty], $path);
                            } else {
                                $subErrors[] = new ErrorMessage('Must be an array.', $unresolvedProperty, $path);
                            }

                            foreach ($subErrors as $subError) {
                                $errors[] = $subError;
                            }
                        }

                        unset($unresolvedProperties[array_search($unresolvedProperty, $unresolvedProperties, true)]);
                        unset($properties[$unresolvedProperty]);

                        array_pop($path);
                    } else {
                        if (!$property->isNullable() || $payload[$unresolvedProperty] !== null) {
                            foreach ($property->getRules() as $rule) {
                                if ($rule instanceof ExtractorAwareInterface) {
                                    $rule->setExtractor($this->extractor);
                                }

                                if ($rule instanceof ValidatorAwareInterface) {
                                    $rule->setValidator($this);
                                }

                                try {
                                    $rule->validate($payload, $unresolvedProperty);
                                } catch (ValidationFailedException $e) {
                                    $errors[] = new ErrorMessage($e->getMessage(), $unresolvedProperty, $path, null, $e->getErrors());
                                }
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
                $message = sprintf('Property is required.');
                $errors[] = new ErrorMessage($message, $name, $path);
            }
        }

        return $errors;
    }
}
