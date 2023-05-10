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
        foreach ($this->sourceProperties as $sourceProperty) {
            $oneFilled = $oneFilled || ($this->getExtractor()->exists($payload, $sourceProperty) && $this->getExtractor()->extract($payload, $sourceProperty) !== null);
            $oneNonFilled = $oneNonFilled || (!$this->getExtractor()->exists($payload, $sourceProperty) || $this->getExtractor()->extract($payload, $sourceProperty) === null);

            if ($oneFilled && $oneNonFilled) {
                $this->checkRequirements($payload);
                break;
            }
        }
    }
}
