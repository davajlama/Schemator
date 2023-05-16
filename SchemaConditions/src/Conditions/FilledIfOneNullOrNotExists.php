<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaConditions\Conditions;

class FilledIfOneNullOrNotExists extends BaseCondition
{
    use FilledIf;

    public function validate(mixed $payload): void
    {
        $oneNullOrNotExists = false;
        foreach ($this->targetProperties as $targetProperty) {
            if (!$this->getExtractor()->exists($payload, $targetProperty) || $this->getExtractor()->extract($payload, $targetProperty) === null) {
                $oneNullOrNotExists = true;
                break;
            }
        }

        if ($oneNullOrNotExists) {
            $this->checkRequirements($payload);
        }
    }
}
