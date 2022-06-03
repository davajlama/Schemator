<?php

declare(strict_types=1);

namespace Davajlama\Schemator;

use LogicException;

trait ValidatorAware
{
    private ?ValidatorInterface $validator = null;

    public function getValidator(): ValidatorInterface
    {
        if ($this->validator === null) {
            throw new LogicException('None validator.');
        }

        return $this->validator;
    }

    public function setValidator(ValidatorInterface $validator): void
    {
        $this->validator = $validator;
    }
}
