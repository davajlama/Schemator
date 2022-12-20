<?php

declare(strict_types=1);

use Davajlama\Schemator\OpenApi\OpenApiBuilder;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/HomepageSchema.php';


$builder = new OpenApiBuilder();

echo PHP_EOL . PHP_EOL;
echo $builder->parse(file_get_contents(__DIR__ . '/api.yaml'));
echo PHP_EOL . PHP_EOL;
