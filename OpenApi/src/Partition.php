<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi;

final class Partition
{
    /**
     * @var callable
     */
    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public static function apply(Api $api, Partition $partition): void
    {
        ($partition->callback)($api);
    }

    public static function create(callable $callback): self
    {
        return new self($callback);
    }
}
