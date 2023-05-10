<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaConditions\Conditions;

class RequiredIfOneNotExists extends BaseCondition
{
    use RequiredIf;

    public function validate(mixed $payload): void
    {
        $oneNotExists = false;
        foreach ($this->targetProperties as $targetProperty) {
            if (!$this->getExtractor()->exists($payload, $targetProperty)) {
                $oneNotExists = true;
                break;
            }
        }

        if ($oneNotExists === true) {
            $this->checkRequirements($payload);
        }
    }
}
