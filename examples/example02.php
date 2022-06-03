<?php

declare(strict_types=1);

use Davajlama\Schemator\Examples\Schema\LetterSchema;
use Davajlama\Schemator\Exception\ValidationFailedException;
use Davajlama\Schemator\Validator\ArrayValidator;
use Davajlama\Schemator\Validator\MessageFormatter;

require_once __DIR__ . '/../vendor/autoload.php';

$payload = [
    'from' => [
        'firstname' => 'Arnold',
        'surname' => 'Rimmer',
        'age' => 30,
    ],
    'to' => [
        'firstname' => 'Dave',
        'surname' => 'Lister',
        'age' => 30,
    ],
    'message' => 'Hi, Dave',
    'photos' => [
        ['url' => 'http://img1', 'description' => 'desc'],
        ['url' => 'http://img2', 'description' => 'desc'],
    ],
];

try {
    (new ArrayValidator())->validate(LetterSchema::class, $payload);
    var_dump('Payload is valid.');
} catch (ValidationFailedException $e) {
    var_dump(MessageFormatter::formatErrors($e->getErrors()));
}
