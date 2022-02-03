<?php

declare(strict_types=1);

namespace Davajlama\Schemator\Tests;

use Davajlama\Schemator\Definition;
use Davajlama\Schemator\Extractor\ArrayExtractor;
use Davajlama\Schemator\Rules\RulesFactory;
use Davajlama\Schemator\Validator;
use PHPUnit\Framework\TestCase;

final class ValidatorTest extends TestCase
{
    public function testBaseValidation(): void
    {
        $def = $this->prepareBaseDefinition();

        $extractor = new ArrayExtractor();
        $validator = new Validator($extractor);

        $data = ['firstname' => 'Martin'];
        self::assertTrue($validator->validate($def, $data));

        $data = ['firstname' => 'David'];
        self::assertFalse($validator->validate($def, $data));
    }

    public function testAllowedAdditionalProperties(): void
    {
        $def = $this->prepareBaseDefinition();
        $def->additionalProperties(true);

        $extractor = new ArrayExtractor();
        $validator = new Validator($extractor);

        $data = ['firstname' => 'Martin', 'lastname' => 'Stark'];
        self::assertTrue($validator->validate($def, $data));
    }

    public function testDeniedAdditionalProperties(): void
    {
        $def = $this->prepareBaseDefinition();

        $extractor = new ArrayExtractor();
        $validator = new Validator($extractor);

        $data = ['firstname' => 'Martin', 'lastname' => 'Stark'];
        self::assertFalse($validator->validate($def, $data));
    }

    public function testSuccessReferencedDefinition(): void
    {
        $contactDefinition = new Definition('Contact');
        $contactDefinition->property('firstname', true)->nonEmptyString();
        $contactDefinition->property('surname', true)->nonEmptyString();


        $orderDefinition = new Definition();
        $orderDefinition->property('id', true)->nonEmptyString();
        $orderDefinition->property('fromContact', true, $contactDefinition);
        $orderDefinition->property('toContact', true, $contactDefinition);

        $extractor = new ArrayExtractor();
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

        self::assertTrue($validator->validate($orderDefinition, $data));
    }

    public function testFailedReferencedDefinition(): void
    {
        $contactDefinition = new Definition('Contact');
        $contactDefinition->property('firstname', true)->nonEmptyString();
        $contactDefinition->property('surname', true)->nonEmptyString();


        $orderDefinition = new Definition();
        $orderDefinition->property('id', true)->nonEmptyString();
        $orderDefinition->property('fromContact', true, $contactDefinition);
        $orderDefinition->property('toContact', true, $contactDefinition);

        $extractor = new ArrayExtractor();
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

        self::assertFalse($validator->validate($orderDefinition, $data));
    }

    public function testSuccessArrayOf(): void
    {
        $contactDefinition = new Definition('Contact');
        $contactDefinition->property('firstname', true)->nonEmptyString();
        $contactDefinition->property('surname', true)->nonEmptyString();

        $contactListDefinition = new Definition();
        $contactListDefinition->property('contacts')->arrayOf($contactDefinition);

        $data = [
            'contacts' => [
                ['firstname' => 'Dave', 'surname' => 'Lister'],
                ['firstname' => 'Arnold', 'surname' => 'Rimmer'],
            ]
        ];

        $extractor = new ArrayExtractor();
        $validator = new Validator($extractor);

        $result = $validator->validate($contactListDefinition, $data);
        //$validator->dumpErrors();
        self::assertTrue($result);
    }

    public function testFailedArrayOf(): void
    {
        $contactDefinition = new Definition('Contact');
        $contactDefinition->property('firstname', true)->nonEmptyString();
        $contactDefinition->property('surname', true)->nonEmptyString();

        $contactListDefinition = new Definition();
        $contactListDefinition->property('contacts')->arrayOf($contactDefinition);

        $data = [
            'contacts' => [
                ['firstname' => 'Dave', 'surname' => 'Lister'],
                ['firstname' => 123, 'surname' => 'Lister'],
                ['firstname' => 'Arnold'],
            ]
        ];

        $extractor = new ArrayExtractor();
        $validator = new Validator($extractor);

        $result = $validator->validate($contactListDefinition, $data);
        //$validator->dumpErrors();
        self::assertFalse($result);
    }

    protected function testRequiredFields()
    {
        $definition = new Definition();
        $definition->property('firstname', true)->string();
        $definition->property('surname', true)->string();
        $definition->property('street', true)->string();
        $definition->property('city', true)->string();

        $extractor = new ArrayExtractor();
        $validator = new Validator($extractor);

        self::assertFalse($validator->validate($definition, []));
        self::assertCount(4, $validator->getErrors());
    }

    protected function prepareBaseDefinition(): Definition
    {
        $def = new Definition();
        $def->property('firstname')
            ->string()
            ->callback(fn(string $value) => $value !== 'David');

        return $def;
    }
}