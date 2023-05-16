<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaConditions\Conditions;

class FilledIfAllNullOrNotExists extends BaseCondition
{
    use FilledIf;

    public function validate(mixed $payload): void
    {
        $allNullOrNotExists = true;
        foreach ($this->targetProperties as $targetProperty) {
            if ($this->getExtractor()->exists($payload, $targetProperty) && $this->getExtractor()->extract($payload, $targetProperty) !== null) {
                $allNullOrNotExists = false;
                break;
            }
        }

        if ($allNullOrNotExists) {
            $this->checkRequirements($payload);
        }
    }
}
