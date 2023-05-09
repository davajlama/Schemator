<?php

declare(strict_types=1);

namespace Davajlama\Schemator\SchemaConditions\Conditions;

use Davajlama\Schemator\Schema\Extractor\ExtractorAware;
use Davajlama\Schemator\Schema\Extractor\ExtractorAwareInterface;
use Davajlama\Schemator\SchemaConditions\ConditionInterface;

abstract class BaseCondition implements ConditionInterface, ExtractorAwareInterface
{
    use ExtractorAware;

    /**
     * @var string[]
     */
    protected array $sourceProperties;

    /**
     * @var string[]
     */
    protected array $targetProperties;

    /**
     * @param string[] $sourceProperties
     * @param string[] $targetProperties
     */
    public function __construct(array $sourceProperties, array $targetProperties)
    {
        $this->sourceProperties = $sourceProperties;
        $this->targetProperties = $targetProperties;
    }

    abstract public function validate(mixed $payload): void;
}
