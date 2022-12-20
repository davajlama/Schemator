<?php

use Davajlama\Schemator\OpenApi\OpenApiBuilder;
use Davajlama\Schemator\OpenApi\SwaggerBuilder;

error_reporting(E_ALL);
ini_set('display_errors', 'On');

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Schema/Author.php';
require_once __DIR__ . '/Schema/Article.php';
require_once __DIR__ . '/Schema/Articles.php';
require_once __DIR__ . '/Schema/ArticlesFilter.php';

//$jsonSchemaGenerator = new Davajlama\JsonSchemaGenerator\SchemaGenerator();
//$schema = $jsonSchemaGenerator->buildToJson(new \Schema\Articles());
//echo $schema;exit;

$openApiBuilder = new OpenApiBuilder();
$openApi = $openApiBuilder->buildArrayFromFile(__DIR__ . '/resources/api.yaml');

$swaggerBuilder = new SwaggerBuilder();
echo $swaggerBuilder->buildFromArray($openApi, 'Demo documentation');

