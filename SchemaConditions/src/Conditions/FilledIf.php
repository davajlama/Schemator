<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaConditions\Conditions;

use Davajlama\Schemator\Schema\Exception\ValidationFailedException;
use Davajlama\Schemator\Schema\Validator\ErrorMessage;

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
                $messages[] = new ErrorMessage('Property is required.', $requiredProperty);
            }

            foreach ($nonFilledProperties as $nonFilledProperty) {
                $messages[] = new ErrorMessage('Property cannot be null.', $nonFilledProperty);
            }

            throw new ValidationFailedException('Required properties.', $messages);
        }
    }
}
