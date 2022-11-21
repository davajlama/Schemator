<?php

declare(strict_types=1);

namespace Davajlama\JsonSchemaGenerator;

final class Schema extends Definition
{
    private string $schema = 'http://json-schema.org/draft-07/schema#';

    /**
     * @return array<string, mixed>
     */
    public function build(): array
    {
        return $this->join([
            '$schema' => $this->schema,
        ], parent::build());
    }
}
