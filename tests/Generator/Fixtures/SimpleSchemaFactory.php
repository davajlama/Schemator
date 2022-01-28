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
        //Examples::firstname();
        //Examples::lastname();

        $rulesFactory = new RulesFactory();
        $contactDefinition = new Definition($rulesFactory, 'Contact');
        $contactDefinition->property('firstname', true)->notEmptyStringType();
        $contactDefinition->property('surname', true)->notEmptyStringType();

        $orderDefinition = new Definition($rulesFactory);
        $orderDefinition->property('id', true)->notEmptyStringType();
        $orderDefinition->property('fromContact', true, $contactDefinition);
        $orderDefinition->property('toContact', true, $contactDefinition);

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