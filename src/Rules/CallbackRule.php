<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Rules;

class CallbackRule extends BaseRule
{
    /**
     * @var callable
     */
    private $callback;

    public function __construct(callable $callback, ?string $message = null)
    {
        parent::__construct($message);

        $this->callback = $callback;
    }

    public function validateValue($value): void
    {
        if (($this->callback)($value) === false) {
            $this->fail('Callback fail!');
        }
    }
}
