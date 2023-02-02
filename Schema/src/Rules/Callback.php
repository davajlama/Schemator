<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Rules;

use Davajlama\Schemator\Schema\Extractor\ExtractorAware;
use Davajlama\Schemator\Schema\Extractor\ExtractorAwareInterface;
use Davajlama\Schemator\Schema\RuleInterface;

class Callback implements RuleInterface, ExtractorAwareInterface
{
    use ExtractorAware;

    /**
     * @var callable
     */
    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function validate(mixed $data, string $property): void
    {
        ($this->callback)($data, $property, $this->getExtractor());
    }
}
