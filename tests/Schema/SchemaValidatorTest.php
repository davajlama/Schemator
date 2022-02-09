<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Tests\Schema;

use Davajlama\Schemator\Definition;
use Davajlama\Schemator\Fixty;
use Davajlama\Schemator\Schema;
use Davajlama\Schemator\Schema\SchemaValidator;
use Davajlama\Schemator\Tests\Generator\Fixtures\SimpleSchemaFactory;
use PHPUnit\Framework\TestCase;

final class SchemaValidatorTest extends TestCase
{
    public function testSuccessValidation(): void
    {
        $schema = SimpleSchemaFactory::create();

        $validator = new SchemaValidator();
        self::assertTrue($validator->validate($schema));
    }

    public function testFailedTitleValidation(): void
    {
        $definition = new Definition();
        $definition->property('name')->string();

        $schema = new Schema($definition);
        $schema->property('name')->examples(Fixty::firstname());

        $validator = new SchemaValidator();
        self::assertFalse($validator->validate($schema, SchemaValidator::VALID_TITLES));
        self::assertTrue($validator->validate($schema, SchemaValidator::VALID_EXAMPLES));
    }

    public function testFailedExamplesValidation(): void
    {
        $definition = new Definition();
        $definition->property('name')->string();

        $schema = new Schema($definition);
        $schema->property('name')->title('Name');

        $validator = new SchemaValidator();
        self::assertTrue($validator->validate($schema, SchemaValidator::VALID_TITLES));
        self::assertFalse($validator->validate($schema, SchemaValidator::VALID_EXAMPLES));
    }
}
