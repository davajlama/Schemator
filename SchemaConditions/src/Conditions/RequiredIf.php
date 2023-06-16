<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaConditions\Conditions;

use Davajlama\Schemator\Schema\Exception\ValidationFailedException;
use Davajlama\Schemator\Schema\Validator\Message;
use Davajlama\Schemator\Schema\Validator\PropertyError;

use function count;

trait RequiredIf
{
    protected function checkRequirements(mixed $payload): void
    {
        $requiredProperties = [];
        foreach ($this->sourceProperties as $sourceProperty) {
            if (!$this->getExtractor()->exists($payload, $sourceProperty)) {
                $requiredProperties[] = $sourceProperty;
            }
        }

        if (count($requiredProperties) > 0) {
            $messages = [];
            foreach ($requiredProperties as $requiredProperty) {
                $messages[] = new PropertyError(new Message('Property is required.'), $requiredProperty);
            }

            throw new ValidationFailedException(new Message('Required properties.'), $messages);
        }
    }
}
