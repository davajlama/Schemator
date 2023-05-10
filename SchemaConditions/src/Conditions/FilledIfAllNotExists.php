<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaConditions\Conditions;

class FilledIfAllNotExists extends BaseCondition
{
    use FilledIf;

    public function validate(mixed $payload): void
    {
        $allNotExists = true;
        foreach ($this->targetProperties as $targetProperty) {
            if ($this->getExtractor()->exists($payload, $targetProperty)) {
                $allNotExists = false;
                break;
            }
        }

        if ($allNotExists === true) {
            $this->checkRequirements($payload);
        }
    }
}
