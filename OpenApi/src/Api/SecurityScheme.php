<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi\Api;

use Davajlama\Schemator\OpenApi\DefinitionInterface;
use Davajlama\Schemator\OpenApi\PropertyHelper;
use LogicException;

use function in_array;
use function sprintf;

class SecurityScheme implements DefinitionInterface
{
    use PropertyHelper;

    public const TYPE_API_KEY = 'apiKey';
    public const TYPE_HTTP = 'http';

    public const IN_HEADER = 'header';
    public const IN_QUERY = 'query';
    public const IN_COOKIE = 'cookie';

    private string $type;

    private ?string $description = null;

    private ?string $name = null;

    private ?string $in = null;

    private ?string $scheme = null;

    private ?string $bearerFormat = null;

    private function __construct(string $type)
    {
        $this->type = $type;
    }

    public static function apiKey(string $name, string $in = self::IN_HEADER): self
    {
        $securityScheme = new self(self::TYPE_API_KEY);
        $securityScheme->name = $name;

        return $securityScheme->in($in);
    }

    public static function httpBasic(): self
    {
        $securityScheme = new self(self::TYPE_HTTP);
        $securityScheme->scheme = 'basic';

        return $securityScheme;
    }

    public static function httpBearer(?string $bearerFormat = null): self
    {
        $securityScheme = new self(self::TYPE_HTTP);
        $securityScheme->scheme = 'bearer';
        $securityScheme->bearerFormat = $bearerFormat;

        return $securityScheme;
    }

    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function in(string $in): self
    {
        if ($this->type !== self::TYPE_API_KEY) {
            throw new LogicException(sprintf('Property [in] is supported only by [%s] security scheme.', self::TYPE_API_KEY));
        }

        if (!in_array($in, [self::IN_HEADER, self::IN_QUERY, self::IN_COOKIE], true)) {
            throw new LogicException(sprintf('Unsupported apiKey location [%s].', $in));
        }

        $this->in = $in;

        return $this;
    }

    /**
     * @return mixed[]
     */
    public function build(): array
    {
        return $this->join(
            $this->prop('type', $this->type),
            $this->prop('description', $this->description),
            $this->prop('name', $this->name),
            $this->prop('in', $this->in),
            $this->prop('scheme', $this->scheme),
            $this->prop('bearerFormat', $this->bearerFormat),
        );
    }
}
