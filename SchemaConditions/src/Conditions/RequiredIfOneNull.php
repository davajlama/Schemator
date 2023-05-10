<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaConditions\Conditions;

class RequiredIfOneNull extends BaseCondition
{
    use RequiredIf;

    public function validate(mixed $payload): void
    {
        $oneNull = false;
        foreach ($this->targetProperties as $targetProperty) {
            if ($this->getExtractor()->exists($payload, $targetProperty) && $this->getExtractor()->extract($payload, $targetProperty) === null) {
                $oneNull = true;
                break;
            }
        }

        if ($oneNull === true) {
            $this->checkRequirements($payload);
        }
    }
}
