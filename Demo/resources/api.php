<?php

declare(strict_types=1);

use Davajlama\Schemator\OpenApi\Api;
use Davajlama\Schemator\OpenApi\Partition;

$api = new Api();
$api->info()->title('Example documentation');
$api->info()->description((string) file_get_contents(__DIR__ . '/description.md'));
$api->info()->version('1.0.0');

Partition::apply($api, Partition::create(function(Api $api) {
    Partition::apply($api, require_once __DIR__ . '/book-store/product.php');
    Partition::apply($api, require_once __DIR__ . '/book-store/author.php');
    Partition::apply($api, require_once __DIR__ . '/book-store/attribute.php');

    Partition::apply($api, require_once __DIR__ . '/book-store/manage.php');
}));

return $api;
