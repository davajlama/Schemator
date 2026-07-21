<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi;

use Davajlama\Schemator\Schema\Schema;
use LogicException;

use function array_keys;
use function array_values;
use function preg_last_error_msg;
use function preg_replace;

class PatternComponentNameResolver extends DefaultComponentNameResolver
{
    /**
     * @var array<string, string>
     */
    private array $patterns;

    /**
     * @param array<string, string> $patterns regular expression as key, replacement as value
     */
    public function __construct(array $patterns)
    {
        $this->patterns = $patterns;
    }

    public function resolve(Schema|string $schema): string
    {
        $name = @preg_replace(array_keys($this->patterns), array_values($this->patterns), $this->resolveName($schema));
        if ($name === null) {
            throw new LogicException('Failed to apply patterns: ' . preg_last_error_msg());
        }

        return $this->capitalize($name);
    }
}
