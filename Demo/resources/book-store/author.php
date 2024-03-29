<?php

declare(strict_types=1);

use Davajlama\Schemator\Demo\BookStore\Schema\Author;
use Davajlama\Schemator\Demo\BookStore\Schema\Authors;
use Davajlama\Schemator\Demo\BookStore\Schema\Problem;
use Davajlama\Schemator\OpenApi\Api;
use Davajlama\Schemator\OpenApi\Partition;

return Partition::create(static function (Api $api): void {
    $ep = $api->get('/book-store/author/list');
    $ep->tags('BookStore', 'BookStore - Public');
    $ep->queryParam('limit');
    $ep->queryParam('offset');
    $ep->jsonResponse200Ok(Authors::class);
    $ep->response500InternalServerError();

    $ep = $api->get('/book-store/author/detail/{id}');
    $ep->tags('BookStore', 'BookStore - Public');
    $ep->pathParam('id', true)->description('Author ID');
    $ep->jsonResponse200Ok(Author::class);
    $ep->jsonResponse404ResourceNotFound(Problem::class);
    $ep->response500InternalServerError();
});
