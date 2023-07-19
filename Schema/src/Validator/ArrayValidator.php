<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Validator;

use Davajlama\Schemator\Schema\Exception\ValidationFailedException;
use Davajlama\Schemator\Schema\Extractor\ArrayExtractor;
use Davajlama\Schemator\Schema\Extractor\ExtractorAwareInterface;
use Davajlama\Schemator\Schema\Extractor\ExtractorInterface;
use Davajlama\Schemator\Schema\Schema;
use Davajlama\Schemator\Schema\SchemaFactory;
use Davajlama\Schemator\Schema\SchemaFactoryAwareInterface;
use Davajlama\Schemator\Schema\SchemaFactoryInterface;
use InvalidArgumentException;

use function array_key_exists;
use function array_keys;
use function array_pop;
use function array_push;
use function array_search;
use function count;
use function is_array;

class ArrayValidator implements ValidatorInterface
{
    private ExtractorInterface $extractor;

    private SchemaFactoryInterface $schemaFactory;

    public function __construct(?SchemaFactoryInterface $schemaFactory = null)
    {
        $this->schemaFactory = $schemaFactory ?? new SchemaFactory();
        $this->extractor = new ArrayExtractor();
    }

    /**
     * @param mixed[] $payload
     */
    public function validate(Schema|string $schema, array $payload): void
    {
        $schema = $this->schemaFactory->create($schema);
        $errors = $this->doValidate($schema, $payload, []);

        if (count($errors) > 0) {
            throw new ValidationFailedException(new Message('Data is not valid.'), $errors);
        }
    }

    /**
     * @param string[] $path
     * @param mixed[] $payload
     * @return PropertyError[]
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
                                $subSubErrors = $this->doValidate($this->schemaFactory->create($property->getReference()), $payload[$unresolvedProperty], $path);
                                if (count($subSubErrors) > 0) {
                                    $subErrors[] = new PropertyError(new Message('Object not valid.'), $unresolvedProperty, $path, null, $subSubErrors);
                                }
                            } else {
                                $subErrors[] = new PropertyError(new Message('Must be an array.'), $unresolvedProperty, $path);
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

                                if ($rule instanceof SchemaFactoryAwareInterface) {
                                    $rule->setSchemaFactory($this->schemaFactory);
                                }

                                try {
                                    $rule->validate($payload, $unresolvedProperty);
                                } catch (ValidationFailedException $e) {
                                    $errors[] = new PropertyError($e->getMessageObject(), $unresolvedProperty, $path, null, $e->getErrors());
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
            $errors[] = new PropertyError(new Message('Additional properties not allowed.'), '*', $path);
        }

        foreach ($properties as $name => $property) {
            if ($property->isRequired()) {
                $errors[] = new PropertyError(new Message('Property is required.'), $name, $path);
            }
        }

        return $errors;
    }
}
