<?php

declare(strict_types=1);

use Davajlama\JsonSchemaGenerator\SchemaGenerator;
use Davajlama\Schemator\Schema;

require_once __DIR__ . '/../../vendor/autoload.php';

$author = new Schema();
$author->prop('firstname')
    ->string()
    ->title('Firstname of customer.')
    ->examples('David');

$author->prop('surname')
    ->string()
    ->nullable()
    ->title('Surname of customer.')
    ->description('This property is non-required.')
    ->examples('Bittner');

$book = new Schema();
$book->prop('id')->integer();
$book->prop('name')->string();
$book->prop('price')->float();
$book->prop('store')->bool();
$book->prop('author')->ref($author);

$generator = new SchemaGenerator();
echo $generator->buildToJson($book);
