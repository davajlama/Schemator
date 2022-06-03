<?php

declare(strict_types=1);

use Davajlama\Schemator\ArrayValidator;
use Davajlama\Schemator\Examples\Schema\ContactSchema;
use Davajlama\Schemator\Exception\ValidationFailedException;
use Davajlama\Schemator\MessagesFormatter;

require_once __DIR__ . '/../vendor/autoload.php';

$validator = new ArrayValidator();

$payload = [
    'firstname' => 'Dave',
    'surname' => 'Lister',
    'age' => 30,
];

try {
    $validator->validate(new ContactSchema(), $payload);
    var_dump('Payload is valid.');
} catch (ValidationFailedException $e) {
    var_dump(MessagesFormatter::formatErrors($e->getErrors()));
}
