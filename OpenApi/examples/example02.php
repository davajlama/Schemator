<?php

use Davajlama\Schemator\OpenApi\Api;

require_once __DIR__ . '/../../vendor/autoload.php';

$api = new Api();
$api->info()->version('1.0.0');
$api->info()->title('Demo API documentation');
$api->info()->description('Demo API description');

$orders = $api->path('/orders/list');
$orders->method('get')
    ->tags('Public')
    ->summary('Get all orders');



var_dump($api->build());