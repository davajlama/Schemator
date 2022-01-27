<?php

declare(strict_types=1);


namespace Davajlama\Schemator\Rules;

class NotEmptyStringTypeRule extends StringTypeRule
{
    public function validateValue($value)
    {
        parent::validateValue($value);

        if (strlen(trim($value)) === 0) {
            $this->fail('String cannot be empty!');
        }
    }

}