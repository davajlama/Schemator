<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Rules;

use Davajlama\Schemator\Extractor\ValueExtractor;

abstract class BaseRule implements Rule, ExtractorAwareInterface
{
    private ?string $message;

    private ?ValueExtractor $extractor = null;

    public function __construct(?string $message = null)
    {
        $this->message = $message;
    }

    public function validate($data, string $property)
    {
        try {
            $value = $this->getExtractor()->extract($data, $property);
            $this->validateValue($value);
        } catch (\InvalidArgumentException $e) {
            $this->fail($this->getMessage($e->getMessage()), $property);
        }
    }

    public function getExtractor(): ValueExtractor
    {
        if($this->extractor === null) {
            throw new \RuntimeException('None extractor');
        }

        return $this->extractor;
    }

    public function setExtractor(ValueExtractor $extractor): void
    {
        $this->extractor = $extractor;
    }

    protected function getMessage(?string $message): string
    {
        return $this->message ?? $message;
    }

    protected function fail(string $message, string $property = null)
    {
        throw new \InvalidArgumentException($message);
    }

    abstract public function validateValue($value);

}