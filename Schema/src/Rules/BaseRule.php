<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Rules;

use Davajlama\Schemator\Schema\Extractor\ExtractorAware;
use Davajlama\Schemator\Schema\Extractor\ExtractorAwareInterface;
use Davajlama\Schemator\Schema\RuleInterface;

abstract class BaseRule implements RuleInterface, ExtractorAwareInterface
{
    use ExtractorAware;

    public function validate(mixed $data, string $property): void
    {
        $value = $this->getExtractor()->extract($data, $property);
        $this->validateValue($value);
    }

    abstract public function validateValue(mixed $value): void;
}
