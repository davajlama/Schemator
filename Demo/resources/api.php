<?php

declare(strict_types=1);

use Davajlama\Schemator\Demo\Schema\Response\Article;
use Davajlama\Schemator\Demo\Schema\Response\Articles;
use Davajlama\Schemator\Demo\Schema\Response\Author;
use Davajlama\Schemator\OpenApi\Api;

$api = new Api();
$api->info()->title('Example documentation');
$api->info()->description('Description of documentation.');
$api->info()->version('1.0.0');

$integrationRun = $api->get('api/v2/user/integration/run');
$integrationRun->tags('User');
$integrationRun->response204NoContent();
$integrationRun->response401AuthorizationRequired();
$integrationRun->response500InternalServerError();

$integrationDetail = $api->get('api/v2/user/integration/detail');
$integrationDetail->tags('User');
$integrationDetail->queryParam('id', true);
$integrationDetail->response200Ok()->json(new Article());
$integrationDetail->response401AuthorizationRequired();
$integrationDetail->response404ResourceNotFound();
$integrationDetail->response500InternalServerError();

$integrationList = $api->get('api/v2/user/integration/list');
$integrationList->tags('User');
$integrationList->response200Ok()->json(new Articles());
$integrationList->response401AuthorizationRequired();
$integrationList->response500InternalServerError();

$integrationCreate = $api->post('api/v2/user/integration/create');
$integrationCreate->tags('User');
$integrationCreate->jsonRequestBody(new Article());
$integrationCreate->jsonResponse200Ok(new Article());
$integrationCreate->response400BadRequest()->json(new Author());
$integrationCreate->response401AuthorizationRequired();
$integrationCreate->response500InternalServerError();

$integrationUpdate = $api->put('api/v2/user/integration/update');
$integrationUpdate->tags('User');
$integrationUpdate->queryParam('id', true);
$integrationUpdate->jsonRequestBody(new Article());
$integrationUpdate->jsonResponse200Ok(new Article());
$integrationUpdate->response400BadRequest()->json(new Author());
$integrationUpdate->response401AuthorizationRequired();
$integrationUpdate->response404ResourceNotFound();
$integrationUpdate->response500InternalServerError();

$integrationDelete = $api->delete('api/v2/user/integration/delete');
$integrationDelete->tags('User');
$integrationDelete->queryParam('id', true);
$integrationDelete->response204NoContent();
$integrationDelete->response401AuthorizationRequired();
$integrationDelete->response404ResourceNotFound();
$integrationDelete->response500InternalServerError();

return $api;
