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
    public function testBaseValidation(): void
    {
        $def = $this->prepareBaseDefinition();

        $extractor = new ArrayValueExtractor();
        $validator = new Validator($extractor);

        $data = ['firstname' => 'Martin'];
        self::assertTrue($validator->validate($def, $data));

        $data = ['firstname' => 'David'];
        self::assertFalse($validator->validate($def, $data));
    }

    public function testAllowedAdditionalProperties(): void
    {
        $def = $this->prepareBaseDefinition(true);

        $extractor = new ArrayValueExtractor();
        $validator = new Validator($extractor);

        $data = ['firstname' => 'Martin', 'lastname' => 'Stark'];
        self::assertTrue($validator->validate($def, $data));
    }

    public function testDeniedAdditionalProperties(): void
    {
        $def = $this->prepareBaseDefinition();

        $extractor = new ArrayValueExtractor();
        $validator = new Validator($extractor);

        $data = ['firstname' => 'Martin', 'lastname' => 'Stark'];
        self::assertFalse($validator->validate($def, $data));
    }

    public function testSuccessReferencedDefinition(): void
    {
        $rulesFactory = new RulesFactory();
        $contactDefinition = new Definition($rulesFactory);
        $contactDefinition->property('firstname', true)->notEmptyStringType();
        $contactDefinition->property('surname', true)->notEmptyStringType();


        $orderDefinition = new Definition($rulesFactory);
        $orderDefinition->property('id', true)->notEmptyStringType();
        $orderDefinition->property('fromContact', true, $contactDefinition);
        $orderDefinition->property('toContact', true, $contactDefinition);

        $extractor = new ArrayValueExtractor();
        $validator = new Validator($extractor);

        $data = [
            'id' => '89a0fb127',
            'fromContact' => [
                'firstname' => 'Jára',
                'surname' => 'Cimrman',
            ],
            'toContact' => [
                'firstname' => 'František',
                'surname' => 'Palacký',
            ]
        ];

        $result = $validator->validate($orderDefinition, $data);
        //$validator->dumpErrors();
        self::assertTrue($result);

        //self::assertTrue($validator->validate($orderDefinition, $data));
    }

    public function testFailedReferencedDefinition(): void
    {
        $rulesFactory = new RulesFactory();
        $contactDefinition = new Definition($rulesFactory);
        $contactDefinition->property('firstname', true)->notEmptyStringType();
        $contactDefinition->property('surname', true)->notEmptyStringType();


        $orderDefinition = new Definition($rulesFactory);
        $orderDefinition->property('id', true)->notEmptyStringType();
        $orderDefinition->property('fromContact', true, $contactDefinition);
        $orderDefinition->property('toContact', true, $contactDefinition);

        $extractor = new ArrayValueExtractor();
        $validator = new Validator($extractor);

        $data = [
            'id' => '89a0fb127',
            'fromContact' => [
                'firstname' => '',
                'surname' => '',
            ],
            'toContact' => [
                'lastname' => 'test',
            ]
        ];

        $result = $validator->validate($orderDefinition, $data);
        $validator->dumpErrors();
        self::assertFalse($result);
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