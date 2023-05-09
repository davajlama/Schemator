<?php

declare(strict_types=1);

use Davajlama\Schemator\Schema\Exception\ValidationFailedException;
use Davajlama\Schemator\Schema\Schema;
use Davajlama\Schemator\Schema\Validator\ArrayValidator;
use Davajlama\Schemator\Schema\Validator\MessageFormatter;

require_once __DIR__ . '/../vendor/autoload.php';

$schema = new Schema();
$schema->prop('firstname')->string()->required();
$schema->prop('surname')->string()->required();
$schema->prop('age')->integer()->required();

$payload = [
    'firstname' => 'Dave',
    'surname' => 'Lister',
    'age' => 30,
];

try {
    (new ArrayValidator())->validate($schema, $payload);
    var_dump('Payload is valid.');
} catch (ValidationFailedException $e) {
    var_dump(MessageFormatter::toFlatten($e->getErrors()));
}
