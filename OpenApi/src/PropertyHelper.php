<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi;

trait PropertyHelper
{
    /**
     * @param string|int|float|bool|mixed[]|null $value
     * @return array<string, string|int|float|bool|mixed[]|object>
     */
    protected function prop(string $key, string|int|float|bool|array|null|object $value): array
    {
        $result = [];
        if ($value !== null) {
            $result[$key] = $value;
        }

        return $result;
    }

    /**
     * @param array<string, mixed> ...$arrays
     * @return array<string, mixed>
     */
    protected function join(array ...$arrays): array
    {
        $joined = [];
        foreach ($arrays as $array) {
            foreach ($array as $key => $value) {
                $joined[$key] = $value;
            }
        }

        return $joined;
    }
}
