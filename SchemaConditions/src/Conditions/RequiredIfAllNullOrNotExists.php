<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaConditions\Conditions;

class RequiredIfAllNullOrNotExists extends BaseCondition
{
    use RequiredIf;

    public function validate(mixed $payload): void
    {
        $allNullOrNotExists = true;
        foreach ($this->targetProperties as $targetProperty) {
            if ($this->getExtractor()->exists($payload, $targetProperty) && $this->getExtractor()->extract($payload, $targetProperty) !== true) {
                $allNullOrNotExists = false;
                break;
            }
        }

        if ($allNullOrNotExists) {
            $this->checkRequirements($payload);
        }
    }
}
