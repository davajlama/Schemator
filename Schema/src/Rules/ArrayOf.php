<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Rules;

use Davajlama\Schemator\Schema\Exception\PropertyIsNotArrayException;
use Davajlama\Schemator\Schema\Exception\ValidationFailedException;
use Davajlama\Schemator\Schema\Schema;
use Davajlama\Schemator\Schema\SchemaFactoryAware;
use Davajlama\Schemator\Schema\SchemaFactoryAwareInterface;
use Davajlama\Schemator\Schema\Validator\ErrorMessage;
use Davajlama\Schemator\Schema\Validator\ValidatorAware;
use Davajlama\Schemator\Schema\Validator\ValidatorAwareInterface;

use function count;
use function is_array;
use function is_string;

class ArrayOf extends BaseRule implements ValidatorAwareInterface, SchemaFactoryAwareInterface
{
    use ValidatorAware;
    use SchemaFactoryAware;

    private Schema|string $schema;

    public function __construct(Schema|string $schema, ?string $message = null)
    {
        parent::__construct($message);

        $this->schema = $schema;
    }

    public function validateValue(mixed $list): void
    {
        if (!is_array($list)) {
            throw new PropertyIsNotArrayException();
        }

        $errors = [];
        foreach ($list as $index => $item) {
            try {
                $this->getValidator()->validate($this->getSchema(), $item);
            } catch (ValidationFailedException $e) {
                foreach ($e->getErrors() as $error) {
                    $errors[] = new ErrorMessage($error->getMessage(), $error->getProperty(), $error->getPath(), $index, $error->getErrors());
                }
            }
        }

        if (count($errors) > 0) {
            $this->fail('Array of items not valid.', $errors);
        }
    }

    private function getSchema(): Schema
    {
        if (is_string($this->schema)) {
            $this->schema = $this->getSchemaFactory()->create($this->schema);
        }

        return $this->schema;
    }
}
