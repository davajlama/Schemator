<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Demo\BookStore\Manage\Response;

use Davajlama\Schemator\SchemaAttributes\Attribute\ArrayOf;
use Davajlama\Schemator\SchemaAttributes\Attribute\RequiredAll;

#[RequiredAll]
final class Authors
{
    /**
     * @param Author[] $list
     */
    public function __construct(
        #[ArrayOf(Author::class)] public array $list,
    ) {
    }
}
