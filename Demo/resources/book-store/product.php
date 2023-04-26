<?php

declare(strict_types=1);

use Davajlama\Schemator\Demo\BookStore\Schema\Problem;
use Davajlama\Schemator\Demo\BookStore\Schema\Product;
use Davajlama\Schemator\Demo\BookStore\Schema\Products;
use Davajlama\Schemator\OpenApi\Api;
use Davajlama\Schemator\OpenApi\Partition;

return Partition::create(static function (Api $api): void {
    $ep = $api->get('/book-store/product/list');
    $ep->tags('BookStore', 'BookStore - Public');
    $ep->queryParam('limit');
    $ep->queryParam('offset');
    $ep->jsonResponse200Ok(Products::class);
    $ep->response500InternalServerError();

    $ep = $api->get('/book-store/product/detail/{id}');
    $ep->tags('BookStore', 'BookStore - Public');
    $ep->pathParam('id', true)->description('Product ID');
    $ep->jsonResponse200Ok(Product::class);
    $ep->jsonResponse404ResourceNotFound(Problem::class);
    $ep->response500InternalServerError();
});
