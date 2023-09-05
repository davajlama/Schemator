<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Arr;

use Davajlama\Schemator\Arr\Exception\ArrException;
use Generator;
use LogicException;

use function array_key_exists;
use function array_slice;
use function count;
use function explode;
use function in_array;
use function is_array;
use function strlen;

class Arr
{
    public const EQUAL = '=';
    public const NOT_EQUAL = '!=';
    public const GREATER_THAN = '>';
    public const LOWER_THAN = '<';
    public const OPERATORS = [
        self::EQUAL,
        self::NOT_EQUAL,
        self::GREATER_THAN,
        self::LOWER_THAN,
    ];

    /**
     * @var mixed[]
     */
    private array $payload;

    /**
     * @var non-empty-string
     */
    private string $separator;

    private bool $strict;

    /**
     * @param mixed[] $payload
     */
    public function __construct(array $payload, bool $strict = false, string $separator = '->')
    {
        if (strlen($separator) === 0) {
            throw new LogicException('Separator cannot be empty string.');
        }

        $this->payload = $payload;
        $this->separator = $separator;
        $this->strict = $strict;
    }

    /**
     * @param mixed[] $payload
     */
    public static function create(array $payload, bool $strict = false, string $separator = '->'): self
    {
        return new self($payload, $strict, $separator);
    }

    public function value(string $path, mixed $default = null, ?bool $strict = null): mixed
    {
        $strict = $strict ?? $this->strict;
        $keys = explode($this->separator, $path);

        $payload = $this->payload;
        foreach ($keys as $key) {
            if ($strict === true && (!is_array($payload) || !array_key_exists($key, $payload))) {
                throw ArrException::propertyNotExists($key);
            }

            if (is_array($payload)) {
                $payload = $payload[$key] ?? $default;
            } else {
                $payload = $default;
            }
        }

        return $payload;
    }

    /**
     * @param mixed[] $default
     */
    public function me(string $path, array $default = [], ?bool $strict = null): self
    {
        $payload = $this->value($path, $default, $strict);
        if (!is_array($payload)) {
            throw ArrException::propertyIsNotArray($path);
        }

        return $this->createSelf($payload);
    }

    public function one(string $key, string $operator, mixed $value): ?self
    {
        $payload = $this->where($key, $operator, $value)->current();
        if (is_array($payload)) {
            return $this->createSelf($payload);
        }

        return null;
    }

    public function the(string $key, string $operator, mixed $value): self
    {
        $me = $this->one($key, $operator, $value);
        if ($me === null) {
            throw ArrException::propertyIsNotArray($key);
        }

        return $me;
    }

    public function all(string $key, string $operator, mixed $value): self
    {
        $payloads = [];
        foreach ($this->where($key, $operator, $value) as $payload) {
            $payloads[] = $payload;
        }

        return $this->createSelf($payloads);
    }

    public function first(): self
    {
        $payload = array_slice($this->payload, 0, 1)[0] ?? null;
        if (!is_array($payload)) {
            throw ArrException::propertyIsNotArray(0);
        }

        return $this->createSelf($payload);
    }

    public function count(): int
    {
        return count($this->payload);
    }

    protected function where(string $key, string $operator, mixed $value): Generator
    {
        if (!in_array($operator, self::OPERATORS, true)) {
            throw new LogicException('Unsupported operator.');
        }

        foreach ($this->payload as $payload) {
            if (is_array($payload) && array_key_exists($key, $payload)) {
                if (
                    $operator === '=' && $payload[$key] === $value
                    || $operator === '!=' && $payload[$key] !== $value
                    || $operator === '>' && $payload[$key] > $value
                    || $operator === '<' && $payload[$key] < $value
                ) {
                    yield $payload;
                }
            }
        }
    }

    /**
     * @param mixed[] $payload
     */
    protected function createSelf(array $payload): self
    {
        return new self($payload, $this->strict, $this->separator);
    }
}
