<?php

declare(strict_types=1);

use Davajlama\Schemator\Demo\BookStore\Manage\Request\AttributeCreate;
use Davajlama\Schemator\Demo\BookStore\Manage\Request\AttributeUpdate;
use Davajlama\Schemator\Demo\BookStore\Manage\Response\Attribute;
use Davajlama\Schemator\Demo\BookStore\Manage\Response\Attributes;
use Davajlama\Schemator\Demo\BookStore\Manage\Response\Problem;
use Davajlama\Schemator\OpenApi\Api;
use Davajlama\Schemator\OpenApi\Partition;

return Partition::create(static function (Api $api): void {
    $ep = $api->get('/book-store/manage/attribute/list');
    $ep->tags('BookStore', 'BookStore - Manage');
    $ep->headerParam('x-api-key', true)->description('User api key');
    $ep->queryParam('limit');
    $ep->queryParam('offset');
    $ep->jsonResponse200Ok(Attributes::class);
    $ep->jsonResponse401AuthorizationRequired(Problem::class);
    $ep->response500InternalServerError();

    $ep = $api->get('/book-store/manage/attribute/detail/{id}');
    $ep->tags('BookStore', 'BookStore - Manage');
    $ep->headerParam('x-api-key', true)->description('User api key');
    $ep->pathParam('id', true)->description('Attribute ID');
    $ep->jsonResponse200Ok(Attribute::class);
    $ep->jsonResponse401AuthorizationRequired(Problem::class);
    $ep->jsonResponse404ResourceNotFound(Problem::class);
    $ep->response500InternalServerError();

    $ep = $api->post('/book-store/manage/attribute/create');
    $ep->tags('BookStore', 'BookStore - Manage');
    $ep->headerParam('x-api-key', true)->description('User api key');
    $ep->jsonRequestBody(AttributeCreate::class);
    $ep->jsonResponse200Ok(Attribute::class);
    $ep->jsonResponse400BadRequest(Problem::class);
    $ep->jsonResponse401AuthorizationRequired(Problem::class);
    $ep->response500InternalServerError();

    $ep = $api->put('/book-store/manage/attribute/update/{id}');
    $ep->tags('BookStore', 'BookStore - Manage');
    $ep->headerParam('x-api-key', true)->description('User api key');
    $ep->pathParam('id', true)->description('Attribute ID');
    $ep->jsonRequestBody(AttributeUpdate::class);
    $ep->jsonResponse200Ok(Attribute::class);
    $ep->jsonResponse400BadRequest(Problem::class);
    $ep->jsonResponse401AuthorizationRequired(Problem::class);
    $ep->jsonResponse404ResourceNotFound(Problem::class);
    $ep->response500InternalServerError();

    $ep = $api->delete('/book-store/manage/attribute/delete/{id}');
    $ep->tags('BookStore', 'BookStore - Manage');
    $ep->headerParam('x-api-key', true)->description('User api key');
    $ep->pathParam('id', true)->description('Attribute ID');
    $ep->response204NoContent();
    $ep->jsonResponse401AuthorizationRequired(Problem::class);
    $ep->jsonResponse404ResourceNotFound(Problem::class);
    $ep->jsonResponse409Conflict(Problem::class);
    $ep->response500InternalServerError();
});
