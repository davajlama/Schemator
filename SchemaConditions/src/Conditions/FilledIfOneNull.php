<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaConditions\Conditions;

class FilledIfOneNull extends BaseCondition
{
    use FilledIf;

    public function validate(mixed $payload): void
    {
        $oneNull = false;
        foreach ($this->targetProperties as $targetProperty) {
            if ($this->getExtractor()->exists($payload, $targetProperty) && $this->getExtractor()->extract($payload, $targetProperty) === null) {
                $oneNull = true;
                break;
            }
        }

        if ($oneNull) {
            $this->checkRequirements($payload);
        }
    }
}
