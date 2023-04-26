<?php

declare(strict_types=1);

use Davajlama\Schemator\Demo\BookStore\Manage\Request\CreateProduct;
use Davajlama\Schemator\Demo\BookStore\Manage\Request\UpdateProduct;
use Davajlama\Schemator\Demo\BookStore\Manage\Response\Problem;
use Davajlama\Schemator\Demo\BookStore\Manage\Response\Product;
use Davajlama\Schemator\Demo\BookStore\Manage\Response\Products;
use Davajlama\Schemator\OpenApi\Api;
use Davajlama\Schemator\OpenApi\Partition;

return Partition::create(static function (Api $api): void {
    $ep = $api->get('/book-store/manage/product/list');
    $ep->tags('BookStore', 'BookStore - Manage');
    $ep->headerParam('x-api-key', true)->description('User api key');
    $ep->queryParam('limit');
    $ep->queryParam('offset');
    $ep->jsonResponse200Ok(Products::class);
    $ep->jsonResponse401AuthorizationRequired(Problem::class);
    $ep->response500InternalServerError();

    $ep = $api->get('/book-store/manage/product/detail/{id}');
    $ep->tags('BookStore', 'BookStore - Manage');
    $ep->headerParam('x-api-key', true)->description('User api key');
    $ep->pathParam('id', true)->description('Product ID');
    $ep->jsonResponse200Ok(Product::class);
    $ep->jsonResponse401AuthorizationRequired(Problem::class);
    $ep->jsonResponse404ResourceNotFound(Problem::class);
    $ep->response500InternalServerError();

    $ep = $api->post('/book-store/manage/product/create');
    $ep->tags('BookStore', 'BookStore - Manage');
    $ep->headerParam('x-api-key', true)->description('User api key');
    $ep->jsonRequestBody(CreateProduct::class);
    $ep->jsonResponse200Ok(Product::class);
    $ep->jsonResponse400BadRequest(Problem::class);
    $ep->jsonResponse401AuthorizationRequired(Problem::class);
    $ep->response500InternalServerError();

    $ep = $api->put('/book-store/manage/product/update/{id}');
    $ep->tags('BookStore', 'BookStore - Manage');
    $ep->headerParam('x-api-key', true)->description('User api key');
    $ep->pathParam('id', true)->description('Product ID');
    $ep->jsonRequestBody(UpdateProduct::class);
    $ep->jsonResponse200Ok(Product::class);
    $ep->jsonResponse400BadRequest(Problem::class);
    $ep->jsonResponse401AuthorizationRequired(Problem::class);
    $ep->jsonResponse404ResourceNotFound(Problem::class);
    $ep->response500InternalServerError();

    $ep = $api->delete('/book-store/manage/product/delete/{id}');
    $ep->tags('BookStore', 'BookStore - Manage');
    $ep->headerParam('x-api-key', true)->description('User api key');
    $ep->pathParam('id', true)->description('Product ID');
    $ep->response204NoContent();
    $ep->jsonResponse401AuthorizationRequired(Problem::class);
    $ep->jsonResponse404ResourceNotFound(Problem::class);
    $ep->jsonResponse409Conflict(Problem::class);
    $ep->response500InternalServerError();

    $ep = $api->patch('/book-store/manage/product/{id}/attribute/{attributeId}/assign');
    $ep->tags('BookStore', 'BookStore - Manage');
    $ep->headerParam('x-api-key', true)->description('User api key');
    $ep->pathParam('id', true)->description('Product ID');
    $ep->pathParam('attributeId', true)->description('Attribute ID');
    $ep->response204NoContent();
    $ep->jsonResponse401AuthorizationRequired(Problem::class);
    $ep->jsonResponse404ResourceNotFound(Problem::class);
    $ep->response500InternalServerError();

    $ep = $api->patch('/book-store/manage/product/{id}/attribute/{attributeId}/delete');
    $ep->tags('BookStore', 'BookStore - Manage');
    $ep->headerParam('x-api-key', true)->description('User api key');
    $ep->pathParam('id', true)->description('Product ID');
    $ep->pathParam('attributeId', true)->description('Attribute ID');
    $ep->response204NoContent();
    $ep->jsonResponse401AuthorizationRequired(Problem::class);
    $ep->jsonResponse404ResourceNotFound(Problem::class);
    $ep->response500InternalServerError();

    $ep = $api->patch('/book-store/manage/product/{id}/author/{authorId}/assign');
    $ep->tags('BookStore', 'BookStore - Manage');
    $ep->headerParam('x-api-key', true)->description('User api key');
    $ep->pathParam('id', true)->description('Product ID');
    $ep->pathParam('authorId', true)->description('Author ID');
    $ep->response204NoContent();
    $ep->jsonResponse401AuthorizationRequired(Problem::class);
    $ep->jsonResponse404ResourceNotFound(Problem::class);
    $ep->response500InternalServerError();

    $ep = $api->patch('/book-store/manage/product/{id}/author/{authorId}/delete');
    $ep->tags('BookStore', 'BookStore - Manage');
    $ep->headerParam('x-api-key', true)->description('User api key');
    $ep->pathParam('id', true)->description('Product ID');
    $ep->pathParam('authorId', true)->description('Author ID');
    $ep->response204NoContent();
    $ep->jsonResponse401AuthorizationRequired(Problem::class);
    $ep->jsonResponse404ResourceNotFound(Problem::class);
    $ep->response500InternalServerError();
});
