<?php

declare(strict_types=1);

use Davajlama\Schemator\JsonSchema\JsonSchemaBuilder;
use Davajlama\Schemator\OpenApi\OpenApiBuilder;
use Davajlama\Schemator\OpenApi\SwaggerBuilder;

error_reporting(E_ALL);
ini_set('display_errors', 'On');

require_once __DIR__ . '/../vendor/autoload.php';

$api = require_once __DIR__ . '/resources/api.php';

if (isset($_GET['dump'])) {
    $openApi = new OpenApiBuilder(new JsonSchemaBuilder());
    $payload = $openApi->build($api);
    var_dump($payload);
    exit;
}

$swagger = new SwaggerBuilder();
echo $swagger->build($api, 'Schemator example');
