<?php

declare(strict_types=1);


namespace Davajlama\Schemator\Schema;

use Davajlama\Schemator\ErrorMessage;
use Davajlama\Schemator\Exception\ValidationFailedException;
use Davajlama\Schemator\Extractor\ArrayValueExtractor;
use Davajlama\Schemator\MessagesFormatter;
use Davajlama\Schemator\Rules\ArrayOf;
use Davajlama\Schemator\Schema;
use Davajlama\Schemator\Validator;

final class SchemaValidator
{
    public const VALID_EXAMPLES = 1;
    public const VALID_TITLES = 2;
    public const VALID_DESCRIPTIONS = 4;
    public const VALID_ALL = self::VALID_EXAMPLES | self::VALID_TITLES | self::VALID_DESCRIPTIONS;

    private $depth = 1;
    private array $errors = [];

    public function validate(Schema $schema, int $level = self::VALID_ALL): bool
    {
        $this->errors = [];
        $this->depth = 1;

        if($level & self::VALID_EXAMPLES) {
            $errors = $this->validateExamples($schema);
            $this->errors = array_merge($this->errors, $errors);
        }

        if($level & self::VALID_TITLES) {
            $errors = $this->validateTitles($schema);
            $this->errors = array_merge($this->errors, $errors);
        }

        // Burn after reading
        if(count($this->errors) > 0) {
            //var_dump(MessagesFormatter::formatErrors($this->errors));
        }

        return count($this->errors) === 0;
    }

    private function validateTitles(Schema $schema): array
    {
        $errors = [];
        foreach($schema->getProperties() as $name => $property) {
            if($property->getTitle() === null) {
                $errors[] = new ErrorMessage('Missing title.', $name);
            }

            if($property->isReferencedDefinition()) {
                $referencedSchema = $schema->getReferencedSchema($property->getReferencedDefinition());
                $errors = array_merge($errors, $this->validateTitles($referencedSchema));
            }
        }

        return $errors;
    }

    private function validateExamples(Schema $schema)
    {
        $errors = [];
        $dataset = [];
        for($i = 1;;$i++) {
            try {
                $dataset[] = $this->mineExamples($schema, $schema->getProperties(), $i - 1);
            } catch (ValidationFailedException $e) {
                $errors = array_merge($errors, $e->getErrors());
            }

            if($i === $this->depth) {
                break;
            }
        }

        $extractor = new ArrayValueExtractor();
        $validator = new Validator($extractor);
        foreach($dataset as $data) {
            $validator->validate($schema->getDefinition(), $data);

            $errors = array_merge($errors, $validator->getErrors());
        }

        return $errors;
    }

    /**
     * @param SchemaProperty[] $properties
     * @param int $index
     * @return mixed[]
     */
    private function mineExamples(Schema $schema, array $properties, int $index): array
    {
        $data = [];
        foreach($properties as $name => $property) {
            $this->depth = max($this->depth, count($property->getExamples()));

            if($property->isReferencedDefinition()) {
                $referencedSchema = $schema->getReferencedSchema($property->getReferencedDefinition());
                $data[$name] = $this->mineExamples($referencedSchema, $referencedSchema->getProperties(), $index);
            } elseif($rulesDefinition = $this->getRulesDefinitions($property->getRules())) {
                if(count($rulesDefinition) !== 1) {
                    throw new \Exception('Still not supported.');
                }
                $referencedSchema = $schema->getReferencedSchema(reset($rulesDefinition));
                $data[$name] = [$this->mineExamples($referencedSchema, $referencedSchema->getProperties(), $index)];
            } else {
                $examples = $property->getExamples();

                if(count($examples) === 0) {
                    $error = new ErrorMessage('No examples.', $name);
                    throw new ValidationFailedException('No examples.', [$error]);
                }

                $data[$name] = array_key_exists($index, $examples) ? $examples[$index] : $examples[0];
            }
        }

        return $data;
    }

    protected function getRulesDefinitions(array $rules): array
    {
        $definitions = [];
        foreach($rules as $rule) {
            switch (get_class($rule)) {
                case ArrayOf::class: $definitions[] = $rule->getDefinition(); break;
            }
        }

        return $definitions;
    }

}