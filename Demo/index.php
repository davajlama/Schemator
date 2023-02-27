<?php

declare(strict_types=1);

use Davajlama\Schemator\Demo\Entity\Product;
use Davajlama\Schemator\JsonSchema\JsonSchemaBuilder;
use Davajlama\Schemator\OpenApi\OpenApiBuilder;
use Davajlama\Schemator\OpenApi\SwaggerBuilder;
use Davajlama\Schemator\SchemaAttributes\SchemaBuilder;

error_reporting(E_ALL);
ini_set('display_errors', 'On');

require_once __DIR__ . '/../vendor/autoload.php';

$api = require_once __DIR__ . '/resources/api.php';

echo '<pre>';
$schemaBuilder = new SchemaBuilder();
$schema = $schemaBuilder->build(Product::class);

var_dump($schema);
exit;

$openApi = new OpenApiBuilder(new JsonSchemaBuilder());
$payload = $openApi->build($api);

if (isset($_GET['dump'])) {
    var_dump($payload);
    exit;
}

$swagger = new SwaggerBuilder();
echo $swagger->buildFromArray($payload, 'Schemator example');
