<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Rules;

use Davajlama\Schemator\Schema\Exception\PropertyIsNotArrayException;
use Davajlama\Schemator\Schema\Exception\ValidationFailedException;
use Davajlama\Schemator\Schema\Schema;
use Davajlama\Schemator\Schema\SchemaFactoryHelper;
use Davajlama\Schemator\Schema\Validator\ErrorMessage;
use Davajlama\Schemator\Schema\Validator\ValidatorAware;
use Davajlama\Schemator\Schema\Validator\ValidatorAwareInterface;

use function count;
use function is_array;

class ArrayOf extends BaseRule implements ValidatorAwareInterface
{
    use ValidatorAware;
    use SchemaFactoryHelper;

    private Schema $schema;

    public function __construct(Schema|string $schema, ?string $message = null)
    {
        parent::__construct($message);

        $this->schema = $this->createSchema($schema);
    }

    public function validateValue(mixed $list): void
    {
        if (!is_array($list)) {
            throw new PropertyIsNotArrayException();
        }

        $errors = [];
        foreach ($list as $index => $item) {
            try {
                $this->getValidator()->validate($this->schema, $item);
            } catch (ValidationFailedException $e) {
                foreach ($e->getErrors() as $error) {
                    $errors[] = new ErrorMessage($error->getMessage(), $error->getProperty(), $error->getPath(), $index);
                }
            }
        }

        if (count($errors) > 0) {
            $this->fail('Array of items not valid.', $errors);
        }
    }
}
