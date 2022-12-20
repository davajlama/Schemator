<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Validator;

interface ValidatorAwareInterface
{
    public function setValidator(ValidatorInterface $validator): void;
}
