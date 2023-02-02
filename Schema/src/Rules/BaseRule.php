<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Rules;

use Davajlama\Schemator\Schema\Exception\ValidationFailedException;
use Davajlama\Schemator\Schema\Extractor\ExtractorAware;
use Davajlama\Schemator\Schema\Extractor\ExtractorAwareInterface;
use Davajlama\Schemator\Schema\RuleInterface;
use Davajlama\Schemator\Schema\Validator\ErrorMessage;

abstract class BaseRule implements RuleInterface, ExtractorAwareInterface
{
    use ExtractorAware;

    private ?string $message;

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
