<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Tests\Generator\Fixtures;

use Davajlama\Schemator\Definition;
use Davajlama\Schemator\Schema;

final class TypedSchemaFactory
{
    public static function create(): Schema
    {
        $definition = new Definition();
        $definition->property('integerId')->integer();
        $definition->property('nullableIntegerId')->nullableInteger();
        $definition->property('stringName')->string();
        $definition->property('nullableStringName')->nullableString();
        $definition->property('nonEmptyStringName')->nonEmptyString();

        $schema = new Schema($definition);
        $schema->title('Typed schema');
        $schema->property('integerId')->examples(666);
        $schema->property('nullableIntegerId')->examples(666, null);
        $schema->property('stringName')->examples('foo', '');
        $schema->property('nullableStringName')->examples('foo', '', null);
        $schema->property('nonEmptyStringName')->examples('foo');

        return $schema;
    }
}