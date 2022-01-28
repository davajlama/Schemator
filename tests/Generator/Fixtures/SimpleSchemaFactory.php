<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Tests\Generator\Fixtures;

use Davajlama\Schemator\Definition;
use Davajlama\Schemator\Rules\RulesFactory;
use Davajlama\Schemator\Schema;

final class SimpleSchemaFactory
{
    public static function create(): Schema
    {
        $bankDefinition = new Definition('Bank');
        $bankDefinition->property('number', true)->integerType();
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

        $contactSchema = new Schema($contactDefinition);
        $contactSchema->title('Contact definition');
        $contactSchema->property('firstname')->title('Firstname');
        $contactSchema->property('surname')->title('Surname');

        $schema = new Schema($orderDefinition, [$contactSchema]);
        $schema->title('Order request schema');
        $schema->description('More and more descriptions');

        $schema->property('id')->title('Order ID')->examples('abcd');
        $schema->property('fromContact')->title('From contact');
        $schema->property('toContact')->title('To contact');

        return $schema;
    }
}