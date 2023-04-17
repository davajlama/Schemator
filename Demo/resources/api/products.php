<?php

declare(strict_types=1);

use Davajlama\Schemator\Demo\Entity\Product;
use Davajlama\Schemator\OpenApi\Api;
use Davajlama\Schemator\OpenApi\Partition;

return Partition::create(static function (Api $api): void {
    $integrationList = $api->get('api/v2/product/find');
    $integrationList->tags('Public');
    $integrationList->response200Ok()->json(Product::class);
    $integrationList->response500InternalServerError();
});
