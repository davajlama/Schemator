<?php

declare(strict_types=1);

namespace Davajlama\Schemator;

interface ValidatorInterface
{
    /**
     * @param mixed[] $payload
     */
    public function validate(Schema $schema, array $payload): void;
}
