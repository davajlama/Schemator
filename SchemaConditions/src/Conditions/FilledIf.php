<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaConditions\Conditions;

use Davajlama\Schemator\Schema\Exception\ValidationFailedException;
use Davajlama\Schemator\Schema\Validator\Message;
use Davajlama\Schemator\Schema\Validator\PropertyError;

use function count;

trait FilledIf
{
    public function checkRequirements(mixed $payload): void
    {
        $requiredProperties = [];
        $nonFilledProperties = [];
        foreach ($this->sourceProperties as $sourceProperty) {
            if (!$this->getExtractor()->exists($payload, $sourceProperty)) {
                $requiredProperties[] = $sourceProperty;
            } elseif ($this->getExtractor()->extract($payload, $sourceProperty) === null) {
                $nonFilledProperties[] = $sourceProperty;
            }
        }

        if (count($requiredProperties) > 0 || count($nonFilledProperties) > 0) {
            $messages = [];
            foreach ($requiredProperties as $requiredProperty) {
                $messages[] = new PropertyError(new Message('Property is required.'), $requiredProperty);
            }

            foreach ($nonFilledProperties as $nonFilledProperty) {
                $messages[] = new PropertyError(new Message('Property cannot be null.'), $nonFilledProperty);
            }

            throw new ValidationFailedException(new Message('Required properties.'), $messages);
        }
    }
}
