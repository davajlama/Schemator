<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Tests\Generator;

use Davajlama\Schemator\Generator\JsonSchemaGenerator;
use Davajlama\Schemator\Tests\Generator\Fixtures\SimpleSchemaFactory;
use PHPUnit\Framework\TestCase;

class JsonSchemaGeneratorTest extends TestCase
{

    public function testSimpleSchemaGeneration(): void
    {
        $schema = SimpleSchemaFactory::create();
        $generator = new JsonSchemaGenerator($schema);
        $result = $generator->generate();

        $jsonContent = (string) file_get_contents(__DIR__ . '/Fixtures/output/simple.schema.json');
        $expectedResult = json_decode($jsonContent, true, 512, JSON_THROW_ON_ERROR);
        self:self::assertEquals($expectedResult, $result);
    }

}