<?php

declare(strict_types=1);

namespace Davajlama\Schemator;

interface ValidatorAwareInterface
{
    public function setValidator(ValidatorInterface $validator): void;
}
