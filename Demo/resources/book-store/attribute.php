<?php

declare(strict_types=1);

use Davajlama\Schemator\Demo\BookStore\Schema\Attribute;
use Davajlama\Schemator\Demo\BookStore\Schema\Attributes;
use Davajlama\Schemator\Demo\BookStore\Schema\Problem;
use Davajlama\Schemator\OpenApi\Api;
use Davajlama\Schemator\OpenApi\Partition;

return Partition::create(static function (Api $api): void {
    $ep = $api->get('/book-store/attribute/list');
    $ep->tags('BookStore', 'BookStore - Public');
    $ep->queryParam('limit');
    $ep->queryParam('offset');
    $ep->jsonResponse200Ok(Attributes::class);
    $ep->response500InternalServerError();

    $ep = $api->get('/book-store/attribute/detail/{id}');
    $ep->tags('BookStore', 'BookStore - Public');
    $ep->pathParam('id', true)->description('Attribute ID');
    $ep->jsonResponse200Ok(Attribute::class);
    $ep->jsonResponse404ResourceNotFound(Problem::class);
    $ep->response500InternalServerError();
});
