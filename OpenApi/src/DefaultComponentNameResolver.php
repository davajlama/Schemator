<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi;

use Davajlama\Schemator\Schema\Schema;

use function explode;
use function strtr;
use function ucfirst;

class DefaultComponentNameResolver implements ComponentNameResolverInterface
{
    public function support(Schema|string $schema): bool
    {
        return true;
    }

    public function resolve(Schema|string $schema): string
    {
        return $this->capitalize($this->resolveName($schema));
    }

    protected function resolveName(Schema|string $schema): string
    {
        if ($schema instanceof Schema) {
            return $schema->getName() ?? $schema::class;
        }

        return $schema;
    }

    protected function capitalize(string $name): string
    {
        $result = '';

        $name = strtr($name, '\\', '/');
        foreach (explode('/', $name) as $word) {
            $result .= ucfirst($word);
        }

        return $result;
    }
}
