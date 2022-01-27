<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Tests;

use Davajlama\Schemator\Definition;
use Davajlama\Schemator\Extractor\ArrayValueExtractor;
use Davajlama\Schemator\Rules\RulesFactory;
use Davajlama\Schemator\Validator;
use PHPUnit\Framework\TestCase;

final class ValidatorTest extends TestCase
{
    public function testBaseValidation()
    {
        $def = $this->prepareBaseDefinition();

        $extractor = new ArrayValueExtractor();
        $validator = new Validator($extractor);

        $data = ['firstname' => 'Martin'];
        self::assertTrue($validator->validate($def, $data));

        $data = ['firstname' => 'David'];
        self::assertFalse($validator->validate($def, $data));
    }

    public function testAllowedAdditionalProperties()
    {
        $def = $this->prepareBaseDefinition(true);

        $extractor = new ArrayValueExtractor();
        $validator = new Validator($extractor);

        $data = ['firstname' => 'Martin', 'lastname' => 'Stark'];
        self::assertTrue($validator->validate($def, $data));
    }

    public function testDeniedAdditionalProperties()
    {
        $def = $this->prepareBaseDefinition();

        $extractor = new ArrayValueExtractor();
        $validator = new Validator($extractor);

        $data = ['firstname' => 'Martin', 'lastname' => 'Stark'];
        self::assertFalse($validator->validate($def, $data));
    }

    protected function prepareBaseDefinition(bool $additionalProperties = false): Definition
    {
        $rulesFactory = new RulesFactory();
        $def = new Definition($rulesFactory, $additionalProperties);
        $def->property('firstname')
            ->stringType()
            ->callback(fn(string $value) => $value !== 'David');

        return $def;
    }
}