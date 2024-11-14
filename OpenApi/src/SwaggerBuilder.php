<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi;

use function file_get_contents;
use function json_encode;
use function strtr;

final class SwaggerBuilder
{
    private OpenApiBuilder $openApiBuilder;

    public function __construct(?OpenApiBuilder $openApiBuilder = null)
    {
        $this->openApiBuilder = $openApiBuilder ?? new OpenApiBuilder();
    }

    public function build(Api $api, string $title = 'Project documentation', string $version = '5.18.2'): string
    {
        $spec = $this->openApiBuilder->build($api);
        $json = json_encode($spec, JSON_THROW_ON_ERROR);

        $content = (string) file_get_contents(__DIR__ . '/../resources/swagger.tpl');

        return strtr($content, [
            '::title' => $title,
            '::spec' => $json,
            '::version' => $version,
        ]);
    }
}
