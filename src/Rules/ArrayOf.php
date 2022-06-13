<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Rules;

use Davajlama\Schemator\Exception\ValidationFailedException;
use Davajlama\Schemator\Schema;
use Davajlama\Schemator\SchemaFactoryHelper;
use Davajlama\Schemator\Validator\ErrorMessage;
use Davajlama\Schemator\Validator\ValidatorAware;
use Davajlama\Schemator\Validator\ValidatorAwareInterface;

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
            throw new ValidationFailedException('Must be an array.');
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
