<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Demo\BookStore\Manage\Response;

use Davajlama\Schemator\SchemaAttributes\Attribute\ArrayOfString;
use Davajlama\Schemator\SchemaAttributes\Attribute\Required;

final class Problem
{
    /**
     * @param string[] $errors
     */
    public function __construct(
        #[Required] public string $message,
        #[ArrayOfString] public array $errors,
    ) {
    }
}
