<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaConditions\Conditions;

class FilledAllOrNone extends BaseCondition
{
    use FilledIf;

    public function validate(mixed $payload): void
    {
        $oneFilled = false;
        $oneNonFilled = false;
        foreach ($this->targetProperties as $targetProperty) {
            $oneFilled = $oneFilled || ($this->getExtractor()->exists($payload, $targetProperty) && $this->getExtractor()->extract($payload, $targetProperty) !== null);
            $oneNonFilled = $oneNonFilled || (!$this->getExtractor()->exists($payload, $targetProperty) || $this->getExtractor()->extract($payload, $targetProperty) === null);

            if ($oneFilled && $oneNonFilled) {
                $this->checkRequirements($payload);
                break;
            }
        }
    }
}
