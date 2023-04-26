<?php

declare(strict_types=1);

use Davajlama\Schemator\OpenApi\Api;
use Davajlama\Schemator\OpenApi\Partition;

return Partition::create(static function (Api $api): void {
    Partition::apply($api, require_once __DIR__ . '/manage/product.php');
    Partition::apply($api, require_once __DIR__ . '/manage/author.php');
    Partition::apply($api, require_once __DIR__ . '/manage/attribute.php');
});
