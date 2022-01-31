<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Tests\Generator\Fixtures;

use Davajlama\Schemator\Definition;
use Davajlama\Schemator\Fixty;
use Davajlama\Schemator\Schema;

final class SimpleSchemaFactory
{
    public static function create(): Schema
    {
        $bankDefinition = new Definition('Bank');
        $bankDefinition->property('number', true)->stringType();
        $bankDefinition->property('name', true)->stringType();
        $bankDefinition->property('description')->stringType();

        $contactDefinition = new Definition('Contact');
        $contactDefinition->property('firstname', true)->nonEmptyString();
        $contactDefinition->property('surname', true)->nonEmptyString();
        $contactDefinition->property('bank', true, $bankDefinition);

        $packageDefinition = new Definition('Package');
        $packageDefinition->property('weight')->integerType();
        $packageDefinition->property('width')->integerType();
        $packageDefinition->property('height')->integerType();

        $orderDefinition = new Definition();
        $orderDefinition->property('id', true)->nonEmptyString();
        $orderDefinition->property('fromContact', true, $contactDefinition);
        $orderDefinition->property('toContact', true, $contactDefinition);
        $orderDefinition->property('packages', true)->arrayOf($packageDefinition);

        $bankSchema = new Schema($bankDefinition);
        $bankSchema->title('Bank description');
        $bankSchema->property('number')->title('Bank number')->examples(Fixty::bankAccountNumber());
        $bankSchema->property('name')->title('Bank name')->examples(Fixty::bankName());
        $bankSchema->property('description')->title('Description')->examples('Bank description');

        $packageSchema = new Schema($packageDefinition);
        $packageSchema->title('Package description');
        $packageSchema->property('weight')->title('Weight')->examples(20);
        $packageSchema->property('width')->title('Width')->examples(150);
        $packageSchema->property('height')->title('Height')->examples(45);

        $contactSchema = new Schema($contactDefinition, [$bankSchema]);
        $contactSchema->title('Contact definition');
        $contactSchema->property('firstname')->title('Firstname')->examples(Fixty::firstname());
        $contactSchema->property('surname')->title('Surname')->examples(Fixty::surname());
        $contactSchema->property('bank')->title('Customer bank account info');

        $schema = new Schema($orderDefinition, [$contactSchema, $packageSchema]);
        $schema->title('Order request schema');
        $schema->description('More and more descriptions');

        $schema->property('id')->title('Order ID')->examples('abcd');
        $schema->property('fromContact')->title('From contact');
        $schema->property('toContact')->title('To contact');
        $schema->property('packages')->title('List of packages');
        return $schema;
    }
}