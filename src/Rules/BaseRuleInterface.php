<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Rules;

use Davajlama\Schemator\ErrorMessage;
use Davajlama\Schemator\Exception\ValidationFailedException;
use Davajlama\Schemator\Extractor\ExtractorAwareInterface;
use Davajlama\Schemator\Extractor\ExtractorInterface;
use Davajlama\Schemator\RuleInterface;
use RuntimeException;

abstract class BaseRuleInterface implements RuleInterface, ExtractorAwareInterface
{
    private ?string $message;

    private ?ExtractorInterface $extractor = null;

    public function __construct(?string $message = null)
    {
        $this->message = $message;
    }

    public function validate(mixed $data, string $property): void
    {
        try {
            $value = $this->getExtractor()->extract($data, $property);
            $this->validateValue($value);
        } catch (ValidationFailedException $e) {
            $this->fail($this->getMessage($e->getMessage()), $e->getErrors());
        }
    }

    public function getExtractor(): ExtractorInterface
    {
        if ($this->extractor === null) {
            throw new RuntimeException('None extractor');
        }

        return $this->extractor;
    }

    public function setExtractor(ExtractorInterface $extractor): void
    {
        $this->extractor = $extractor;
    }

    protected function getMessage(?string $message): string
    {
        return (string) ($this->message ?? $message);
    }

    /**
     * @param ErrorMessage[] $errors
     */
    protected function fail(string $message, array $errors = []): ValidationFailedException
    {
        throw new ValidationFailedException($message, $errors);
    }

    abstract public function validateValue(mixed $value): void;
}
