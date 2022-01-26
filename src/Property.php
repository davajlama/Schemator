<?php

declare(strict_types=1);


namespace Davajlama\Schemator;

use Davajlama\Schemator\Extractor\ValueExtractor;
use Davajlama\Schemator\Rules\Rule;
use Davajlama\Schemator\Rules\RulesFactory;

class Property
{
    private RulesFactory $rulesFactory;

    /** @var Rule[] */
    private array $rules = [];

    public function __construct(RulesFactory $rulesFactory)
    {
        $this->rulesFactory = $rulesFactory;
    }

    public function stringType(?string $message = null): self
    {
        $this->rules[] = $this->rulesFactory->createStringTypeRule($message);

        return $this;
    }

    public function callback(callable $callback, ?string $message = null): self
    {
        $this->rules[] = $this->rulesFactory->createCallbackRule($callback, $message);

        return $this;
    }

    public function getRules(): array
    {
        return $this->rules;
    }

}