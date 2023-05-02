<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Demo\BookStore\Manage\Request;

use Davajlama\Schemator\SchemaAttributes\Attribute\ArrayOfValues;

final class ProductSearch
{
    /**
     * @param string[] $type
     */
    public function __construct(
        #[ArrayOfValues(['BOOK', 'MAGAZINE'])] public ?array $type = null,
    ) {
    }
}
