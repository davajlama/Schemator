<?php

declare(strict_types=1);

use Davajlama\Schemator\Demo\Schema\Response\Articles;
use Davajlama\Schemator\OpenApi\Api;
use Davajlama\Schemator\OpenApi\Partition;

return Partition::create(static function (Api $api): void {
    $integrationList = $api->get('api/v2/recent-sales/find');
    $integrationList->tags('Public');
    $integrationList->response200Ok()->json(new Articles());
    $integrationList->response500InternalServerError();
});
