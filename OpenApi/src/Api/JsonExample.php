<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi\Api;

use JsonException;
use LogicException;

use function is_array;
use function json_decode;

class JsonExample extends Example
{
    public function __construct(string $json, ?string $summary = null)
    {
        try {
            $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            if (!is_array($data)) {
                throw new LogicException('Decoded JSON is not an array.');
            }
        } catch (JsonException $e) {
            throw new LogicException('Invalid json.');
        }

        parent::__construct($data, $summary);
    }
}
