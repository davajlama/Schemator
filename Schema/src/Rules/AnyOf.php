<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Rules;

use Davajlama\Schemator\Schema\Exception\PropertyIsNotArrayException;
use Davajlama\Schemator\Schema\Exception\PropertyIsNotStringException;
use Davajlama\Schemator\Schema\Exception\ValidationFailedException;
use Davajlama\Schemator\Schema\Schema;
use Davajlama\Schemator\Schema\SchemaFactoryAware;
use Davajlama\Schemator\Schema\SchemaFactoryAwareInterface;
use Davajlama\Schemator\Schema\Validator\Message;
use Davajlama\Schemator\Schema\Validator\ValidatorAware;
use Davajlama\Schemator\Schema\Validator\ValidatorAwareInterface;

use function array_key_exists;
use function is_array;
use function is_string;

final class AnyOf extends BaseRule implements ValidatorAwareInterface, SchemaFactoryAwareInterface
{
    use ValidatorAware;
    use SchemaFactoryAware;

    private string $typeProperty;

    /**
     * @var array<string, Schema|string>
     */
    private array $mapping;

    /**
     * @param array<string, Schema|string> $mapping
     */
    public function __construct(string $typeProperty, array $mapping)
    {
        $this->typeProperty = $typeProperty;
        $this->mapping = $mapping;
    }

    public function validateValue(mixed $value): void
    {
        if (!is_array($value)) {
            throw new PropertyIsNotArrayException();
        }

        $this->getValidator()->validate($this->getTypeSchema($value), $value);
    }

    private function getTypeSchema(mixed $value): Schema
    {
        $class = $this->getExtractor()->extract($value, $this->typeProperty);
        if (!is_string($class)) {
            throw new PropertyIsNotStringException();
        }

        if (!array_key_exists($class, $this->mapping)) {
            throw new ValidationFailedException(new Message('Unsupported type of allowed objects.'));
        }

        $schema = $this->mapping[$class];
        if (is_string($schema)) {
            $schema = $this->getSchemaFactory()->create($schema);
        }

        return $schema;
    }
}
