<?php

declare(strict_types=1);


namespace Davajlama\Schemator\Tests\Schema;

use Davajlama\Schemator\Schema\SchemaValidator;
use Davajlama\Schemator\Tests\Generator\Fixtures\SimpleSchemaFactory;
use Davajlama\Schemator\Tests\Generator\Fixtures\TypedSchemaFactory;
use PHPUnit\Framework\TestCase;

final class SchemaValidatorTest extends TestCase
{
    public function testSuccessValidation()
    {
        $schema = SimpleSchemaFactory::create();

        $validator = new SchemaValidator();
        self::assertTrue($validator->validate($schema));
    }
}