<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaConditions\Conditions;

class RequiredIfAllNull extends BaseCondition
{
    use RequiredIf;

    public function validate(mixed $payload): void
    {
        $allNull = true;
        foreach ($this->targetProperties as $targetProperty) {
            if (!$this->getExtractor()->exists($payload, $targetProperty) || $this->getExtractor()->extract($payload, $targetProperty) !== null) {
                $allNull = false;
                break;
            }
        }

        if ($allNull === true) {
            $this->checkRequirements($payload);
        }
    }
}
