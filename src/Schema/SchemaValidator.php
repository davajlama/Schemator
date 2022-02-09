<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema;

use Davajlama\Schemator\Definition;
use Davajlama\Schemator\ErrorMessage;
use Davajlama\Schemator\Exception\ValidationFailedException;
use Davajlama\Schemator\Extractor\ArrayExtractor;
use Davajlama\Schemator\Rules\ArrayOf;
use Davajlama\Schemator\Rules\Rule;
use Davajlama\Schemator\Schema;
use Davajlama\Schemator\Validator;
use Exception;

use function array_key_exists;
use function array_merge;
use function count;
use function max;
use function reset;

final class SchemaValidator
{
    public const VALID_EXAMPLES = 1;
    public const VALID_TITLES = 2;
    public const VALID_DESCRIPTIONS = 4;
    public const VALID_ALL = self::VALID_EXAMPLES | self::VALID_TITLES | self::VALID_DESCRIPTIONS;

    private int $depth = 1;

    /**
     * @var ErrorMessage[]
     */
    private array $errors = [];

    public function validate(Schema $schema, int $level = self::VALID_ALL): bool
    {
        $this->errors = [];
        $this->depth = 1;

        $doValidExamples = (bool) ($level & self::VALID_EXAMPLES);
        if ($doValidExamples) {
            $errors = $this->validateExamples($schema);
            $this->errors = array_merge($this->errors, $errors);
        }

        $doValidTitles = (bool) ($level & self::VALID_TITLES);
        if ($doValidTitles) {
            $errors = $this->validateTitles($schema);
            $this->errors = array_merge($this->errors, $errors);
        }

        return count($this->errors) === 0;
    }

    /**
     * @return ErrorMessage[]
     */
    private function validateTitles(Schema $schema): array
    {
        $errors = [];
        foreach ($schema->getProperties() as $name => $property) {
            if ($property->getTitle() === null) {
                $errors[] = new ErrorMessage('Missing title.', $name);
            }

            if ($property->isReferencedDefinition()) {
                $referencedSchema = $schema->getReferencedSchema($property->getReferencedDefinition());
                $errors = array_merge($errors, $this->validateTitles($referencedSchema));
            }
        }

        return $errors;
    }

    /**
     * @return ErrorMessage[]
     */
    private function validateExamples(Schema $schema): array
    {
        $errors = [];
        $dataset = [];
        for ($i = 1;; $i++) {
            try {
                $dataset[] = $this->mineExamples($schema, $schema->getProperties(), $i - 1);
            } catch (ValidationFailedException $e) {
                $errors = array_merge($errors, $e->getErrors());
            }

            if ($i === $this->depth) {
                break;
            }
        }

        $extractor = new ArrayExtractor();
        $validator = new Validator($extractor);
        foreach ($dataset as $data) {
            $validator->validate($schema->getDefinition(), $data);

            $errors = array_merge($errors, $validator->getErrors());
        }

        return $errors;
    }

    /**
     * @param SchemaProperty[] $properties
     * @return mixed[]
     */
    private function mineExamples(Schema $schema, array $properties, int $index): array
    {
        $data = [];
        foreach ($properties as $name => $property) {
            $this->depth = max($this->depth, count($property->getExamples()));

            $rulesDefinitions = $this->getRulesDefinitions($property->getRules());

            if ($property->isReferencedDefinition()) {
                $referencedSchema = $schema->getReferencedSchema($property->getReferencedDefinition());
                $data[$name] = $this->mineExamples($referencedSchema, $referencedSchema->getProperties(), $index);
            } elseif (count($rulesDefinitions) > 0) {
                if (count($rulesDefinitions) !== 1) {
                    throw new Exception('Still not supported.');
                }
                $referencedSchema = $schema->getReferencedSchema(reset($rulesDefinitions));
                $data[$name] = [$this->mineExamples($referencedSchema, $referencedSchema->getProperties(), $index)];
            } else {
                $examples = $property->getExamples();

                if (count($examples) === 0) {
                    $error = new ErrorMessage('No examples.', $name);
                    throw new ValidationFailedException('No examples.', [$error]);
                }

                $data[$name] = array_key_exists($index, $examples) ? $examples[$index] : $examples[0];
            }
        }

        return $data;
    }

    /**
     * @param Rule[] $rules
     * @return Definition[]
     */
    protected function getRulesDefinitions(array $rules): array
    {
        $definitions = [];
        foreach ($rules as $rule) {
            switch ($rule::class) {
                case ArrayOf::class:
                    $definitions[] = $rule->getDefinition();
                    break;
            }
        }

        return $definitions;
    }
}
