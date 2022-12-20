<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi;

use function file_get_contents;
use function json_encode;
use function strtr;

final class SwaggerBuilder
{
    public function buildFromArray(array $spec, string $title = 'Project documentation'): string
    {
        $json = json_encode($spec, JSON_THROW_ON_ERROR);

        $content = file_get_contents(__DIR__ . '/../resources/swagger.tpl');

        return strtr($content, [
            '::title' => $title,
            '::spec' => $json,
        ]);
    }
}
