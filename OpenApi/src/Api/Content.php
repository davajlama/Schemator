<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi\Api;

use Davajlama\Schemator\OpenApi\DefinitionInterface;
use Davajlama\Schemator\OpenApi\PropertyHelper;
use Davajlama\Schemator\Schema\Schema;

class Content implements DefinitionInterface
{
    use PropertyHelper;

    private string $type;

    private ?Schema $schema = null;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function build(): array
    {
        return [
            $this->type => $this->join(
                $this->prop('$ref', $this->schema),
            ),
        ];
    }
}