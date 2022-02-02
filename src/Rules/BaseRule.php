<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Rules;

use Davajlama\Schemator\ErrorMessage;
use Davajlama\Schemator\Exception\ValidationFailedException;
use Davajlama\Schemator\Extractor\Extractor;
use Davajlama\Schemator\Extractor\ExtractorAwareInterface;

abstract class BaseRule implements Rule, ExtractorAwareInterface
{
    private ?string $message;

    private ?Extractor $extractor = null;

    public function __construct(?string $message = null)
    {
        $this->message = $message;
    }

    public function validate($data, string $property)
    {
        try {
            $value = $this->getExtractor()->extract($data, $property);
            $this->validateValue($value);
        } catch (ValidationFailedException $e) {
            $this->fail($this->getMessage($e->getMessage()), $e->getErrors());
        }
    }

    public function getExtractor(): Extractor
    {
        if($this->extractor === null) {
            throw new \RuntimeException('None extractor');
        }

        return $this->extractor;
    }

    public function setExtractor(Extractor $extractor): void
    {
        $this->extractor = $extractor;
    }

    protected function getMessage(?string $message): string
    {
        return $this->message ?? $message;
    }

    /**
     * @param ErrorMessage[] $errors
     */
    protected function fail(string $message, array $errors = [])
    {
        throw new ValidationFailedException($message, $errors);
    }

    abstract public function validateValue($value);

}