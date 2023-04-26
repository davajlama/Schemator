<?php

declare(strict_types=1);

use Davajlama\Schemator\Demo\BookStore\Manage\Request\CreateAuthor;
use Davajlama\Schemator\Demo\BookStore\Manage\Request\UpdateAuthor;
use Davajlama\Schemator\Demo\BookStore\Manage\Response\Author;
use Davajlama\Schemator\Demo\BookStore\Manage\Response\Authors;
use Davajlama\Schemator\Demo\BookStore\Manage\Response\Problem;
use Davajlama\Schemator\OpenApi\Api;
use Davajlama\Schemator\OpenApi\Partition;

return Partition::create(static function (Api $api): void {
    $ep = $api->get('/book-store/manage/author/list');
    $ep->tags('BookStore', 'BookStore - Manage');
    $ep->headerParam('x-api-key', true)->description('User api key');
    $ep->queryParam('limit');
    $ep->queryParam('offset');
    $ep->jsonResponse200Ok(Authors::class);
    $ep->jsonResponse401AuthorizationRequired(Problem::class);
    $ep->response500InternalServerError();

    $ep = $api->get('/book-store/manage/author/detail/{id}');
    $ep->tags('BookStore', 'BookStore - Manage');
    $ep->headerParam('x-api-key', true)->description('User api key');
    $ep->pathParam('id', true)->description('Author ID');
    $ep->jsonResponse200Ok(Author::class);
    $ep->jsonResponse401AuthorizationRequired(Problem::class);
    $ep->jsonResponse404ResourceNotFound(Problem::class);
    $ep->response500InternalServerError();

    $ep = $api->post('/book-store/manage/author/create');
    $ep->tags('BookStore', 'BookStore - Manage');
    $ep->headerParam('x-api-key', true)->description('User api key');
    $ep->jsonRequestBody(CreateAuthor::class);
    $ep->jsonResponse200Ok(Author::class);
    $ep->jsonResponse400BadRequest(Problem::class);
    $ep->jsonResponse401AuthorizationRequired(Problem::class);
    $ep->response500InternalServerError();

    $ep = $api->put('/book-store/manage/author/update/{id}');
    $ep->tags('BookStore', 'BookStore - Manage');
    $ep->headerParam('x-api-key', true)->description('User api key');
    $ep->pathParam('id', true)->description('Author ID');
    $ep->jsonRequestBody(UpdateAuthor::class);
    $ep->jsonResponse200Ok(Author::class);
    $ep->jsonResponse400BadRequest(Problem::class);
    $ep->jsonResponse401AuthorizationRequired(Problem::class);
    $ep->jsonResponse404ResourceNotFound(Problem::class);
    $ep->response500InternalServerError();

    $ep = $api->delete('/book-store/manage/author/delete/{id}');
    $ep->tags('BookStore', 'BookStore - Manage');
    $ep->headerParam('x-api-key', true)->description('User api key');
    $ep->pathParam('id', true)->description('Author ID');
    $ep->response204NoContent();
    $ep->jsonResponse401AuthorizationRequired(Problem::class);
    $ep->jsonResponse404ResourceNotFound(Problem::class);
    $ep->jsonResponse409Conflict(Problem::class);
    $ep->response500InternalServerError();
});
