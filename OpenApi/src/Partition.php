<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi;

use Closure;

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

    public static function apply(Api $api, Partition|Closure $partition): void
    {
        if ($partition instanceof Closure) {
            $partition = self::create($partition);
        }

        $decorator = new Decorator();
        $api->getDecorator()->pushDecorator($decorator);
        ($partition->callback)($api, $decorator);
        $api->getDecorator()->popDecorator();
    }

    public static function create(callable $callback): self
    {
        return new self($callback);
    }
}
