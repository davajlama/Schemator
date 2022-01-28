<?php

declare(strict_types=1);


namespace Davajlama\Schemator\Schema;

use Davajlama\Schemator\Extractor\ArrayValueExtractor;
use Davajlama\Schemator\Schema;
use Davajlama\Schemator\Validator;

final class SchemaValidator
{
    private $depth = 1;

    public function validate(Schema $schema)
    {
        $extractor = new ArrayValueExtractor();
        $validator = new Validator($extractor);

        $dataset = [];
        for($i = 1;;$i++) {
            $dataset[] = $this->mineExamples($schema->getProperties(), $i);
            if($i === $this->depth) {
                break;
            }
        }

        $errors = [];
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
    private function mineExamples(array $properties, int $index): array
    {
        $data = [];
        foreach($properties as $name => $property) {
            $this->depth = max($this->depth, count($property->getExamples()));

            if($property->isDefinition()) {
                $data[$name] = $this->mineExamples($property);
            } else {
                $examples = $property->getExamples();
                if(count($examples) === 0) {
                    throw new \RuntimeException('No examples for: ' . $name);
                }

                $data[$name] = $examples[$index] ?? $examples[0];
            }
        }
    }

}