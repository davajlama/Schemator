<?php

use Davajlama\Schemator\OpenApi\Api;
use Davajlama\Schemator\OpenApi\Examples\HomepageSchema;
use Davajlama\Schemator\OpenApi\OpenApiBuilder;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/HomepageSchema.php';

$api = new Api();
$api->info()->version('1.0.0');
$api->info()->title('Demo API documentation');
$api->info()->description('Demo API description');

$orders = $api->path('/orders/list');
$ordersGET = $orders->method('get')
    ->tags('Public')
    ->summary('Get all orders');

$ordersGET->requestBody()
        ->addContent(new Api\JsonContent(new HomepageSchema()));

$ordersGET->response(200)
        ->addContent(new Api\JsonContent(new HomepageSchema()));

$builder = new OpenApiBuilder();
var_dump($builder->build($api));