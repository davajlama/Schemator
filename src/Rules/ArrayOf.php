<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Rules;

use Davajlama\Schemator\Definition;
use Davajlama\Schemator\ErrorMessage;
use Davajlama\Schemator\Exception\ValidationFailedException;
use Davajlama\Schemator\Validator;

use function count;
use function is_array;

class ArrayOf extends BaseRule
{
    private Definition $definition;

    public function __construct(Definition $definition, ?string $message = null)
    {
        parent::__construct($message);

        $this->definition = $definition;
    }

    public function validateValue(mixed $value): void
    {
        if (!is_array($value)) {
            // prdelat na fail, ktery vraci exception
            throw new ValidationFailedException('Value must be an array.');
        }

        $errors = [];
        foreach ($value as $index => $item) {
            $validator = new Validator($this->getExtractor());
            if (!$validator->validate($this->definition, $item)) {
                foreach ($validator->getErrors() as $error) {
                    $errors[] = new ErrorMessage($error->getMessage(), $error->getProperty(), $error->getPath(), $index);
                }
            }
        }

        if (count($errors) > 0) {
            $this->fail('Array of not valid!', $errors);
        }
    }

    public function getDefinition(): Definition
    {
        return $this->definition;
    }
}
