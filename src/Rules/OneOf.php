<?php

declare(strict_types=1);


namespace Davajlama\Schemator\Rules;

class OneOf extends BaseRule
{
    /** @var mixed[] */
    private array $values;

    /**
     * @param mixed[] $values
     */
    public function __construct(array $values, ?string $message = null)
    {
        parent::__construct($message);

        $this->values = $values;
    }

    public function validateValue($value)
    {
        if(!in_array($value, $this->values, true)) {
            $this->fail(sprintf('Supported only one of [%s]', implode(',', $this->values)));
        }
    }

}