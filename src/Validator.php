<?php

declare(strict_types=1);

namespace Davajlama\Schemator;

use Davajlama\Schemator\Exception\ValidationFailedException;
use Davajlama\Schemator\Extractor\Extractor;
use Davajlama\Schemator\Extractor\ExtractorAwareInterface;
use InvalidArgumentException;

use function array_key_exists;
use function array_keys;
use function array_pop;
use function array_push;
use function array_search;
use function count;
use function sprintf;
use function var_dump;

class Validator
{
    private Extractor $extractor;

    /**
     * @var ErrorMessage[]
     */
    private array $errors = [];

    public function __construct(Extractor $extractor)
    {
        $this->extractor = $extractor;
    }

    public function validate(Definition $definition, mixed $data): bool
    {
        $this->errors = $this->doValidate($definition, $data, []);

        return count($this->errors) === 0;
    }

    /**
     * @param string[] $path
     * @return ErrorMessage[]
     */
    protected function doValidate(Definition $definition, mixed $data, array $path): array
    {
        $properties = $definition->getProperties();

        $data = (array) $data;

        $unresolvedProperties = array_keys($data);

        $errors = [];
        foreach ($unresolvedProperties as $unresolvedProperty) {
            if (array_key_exists($unresolvedProperty, $properties)) {
                try {
                    $property = $properties[$unresolvedProperty];

                    if ($property instanceof ReferencedProperty) {
                        array_push($path, $unresolvedProperty);
                        $subErrors = $this->doValidate($property->getReferencedDefinition(), $data[$unresolvedProperty], $path);

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
                                $rule->validate($data, $unresolvedProperty);
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

        if (!$definition->isAdditionalPropertiesAllowed() && count($unresolvedProperties) > 0) {
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

    /**
     * @return ErrorMessage[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function dumpErrors(): void
    {
        var_dump(MessagesFormatter::formatErrors($this->getErrors()));
    }
}
