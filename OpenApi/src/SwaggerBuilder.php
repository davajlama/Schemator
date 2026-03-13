<?php

declare(strict_types=1);

namespace Davajlama\Schemator\OpenApi;

use function file_get_contents;
use function json_encode;
use function strtr;

final class SwaggerBuilder
{
    public const UNSAFE_MARKDOWN = 1 << 0;

    private OpenApiBuilder $openApiBuilder;

    private bool $unsafeMarkdown;

    public function __construct(?OpenApiBuilder $openApiBuilder = null, int $flags = 0)
    {
        $this->openApiBuilder = $openApiBuilder ?? new OpenApiBuilder();

        $this->unsafeMarkdown = ($flags & self::UNSAFE_MARKDOWN) === self::UNSAFE_MARKDOWN;
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
            '::unsafeMarkdown' => $this->unsafeMarkdown ? 'true' : 'false',
        ]);
    }
}
