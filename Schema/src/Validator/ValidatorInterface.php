<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Schema\Validator;

use Davajlama\Schemator\Schema\Schema;

interface ValidatorInterface
{
    /**
     * @param mixed[] $payload
     */
    public function validate(Schema|string $schema, array $payload): void;
}
