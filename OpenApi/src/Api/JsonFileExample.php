<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi\Api;

use LogicException;

use function file_get_contents;
use function is_string;

class JsonFileExample extends JsonExample
{
    public function __construct(string $jsonFile, ?string $summary = null)
    {
        $json = @file_get_contents($jsonFile);
        if (!is_string($json)) {
            throw new LogicException('Unable to read json file.');
        }

        parent::__construct($json, $summary);
    }
}
