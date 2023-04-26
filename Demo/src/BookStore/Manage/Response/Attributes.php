<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Demo\BookStore\Manage\Response;

use Davajlama\Schemator\SchemaAttributes\Attribute\ArrayOf;
use Davajlama\Schemator\SchemaAttributes\Attribute\RequiredAll;

#[RequiredAll]
final class Attributes
{
    /**
     * @param Attribute[] $list
     */
    public function __construct(
        #[ArrayOf(Attribute::class)] public array $list,
    ) {
    }
}
