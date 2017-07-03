<?php

namespace RoundingWell\Schematic;

use PHPUnit\Framework\TestCase;

class SchemaTest extends TestCase
{
    public function testSchema()
    {
        $schema = Schema::fromFile(__DIR__ . '/../example/car.json');

        $this->assertTrue($schema->isObject());
        $this->assertSame('Car', $schema->title());

        $properties = $schema->properties();

        $this->assertTrue(is_array($properties));
        $this->assertArrayHasKey('make', $properties);
        $this->assertArrayHasKey('model', $properties);
        $this->assertArrayHasKey('year', $properties);
        $this->assertArrayHasKey('owners', $properties);

        $this->assertTrue($schema->isRequired('make'));
        $this->assertTrue($schema->isRequired('model'));
        $this->assertFalse($schema->isRequired('year'));
        $this->assertFalse($schema->isRequired('owners'));

        $this->assertTrue($properties['make']->isString());
        $this->assertTrue($properties['model']->isString());
        $this->assertTrue($properties['year']->isInteger());
        $this->assertTrue($properties['owners']->isArray());
        $this->assertTrue($properties['owners']->items()->isObject());
    }

    public function testArray()
    {
        $schema = Schema::make($this->makeJsonObject([
            'type' => 'array',
            'items' => [
                'type' => 'string'
            ]
        ]));

        $this->assertTrue($schema->isArray());
        $this->assertTrue($schema->hasItems());
        $this->assertTrue($schema->items()->isString());
        $this->assertSame('string[]', $schema->phpType());

        $schema = Schema::make($this->makeJsonObject([
            'type' => 'array',
        ]));

        $this->assertTrue($schema->isArray());
        $this->assertFalse($schema->hasItems());
        $this->assertNull($schema->items());
        $this->assertSame('array', $schema->phpType());
    }

    public function testBoolean()
    {
        $schema = Schema::make($this->makeJsonObject([
            'type' => 'boolean',
        ]));

        $this->assertTrue($schema->isBoolean());
        $this->assertSame('bool', $schema->phpType());
    }

    public function testInteger()
    {
        $schema = Schema::make($this->makeJsonObject([
            'type' => 'integer',
        ]));

        $this->assertTrue($schema->isInteger());
        $this->assertSame('int', $schema->phpType());
    }

    public function testNull()
    {
        $schema = Schema::make($this->makeJsonObject([
            'type' => 'null',
        ]));

        $this->assertTrue($schema->isNull());
        $this->assertSame('null', $schema->phpType());
    }

    public function testNumber()
    {
        $schema = Schema::make($this->makeJsonObject([
            'type' => 'number',
        ]));

        $this->assertTrue($schema->isNumber());
        $this->assertSame('float', $schema->phpType());
    }

    public function testObject()
    {
        $schema = Schema::make($this->makeJsonObject([
            'type' => 'object',
            'properties' => [
                'name' => [
                    'type' => 'string',
                ],
            ],
        ]));

        $this->assertTrue($schema->isObject());
        $this->assertTrue(is_array($schema->properties()));
        $this->assertSame('object', $schema->phpType());
    }

    public function testString()
    {
        $schema = Schema::make($this->makeJsonObject([
            'type' => 'string',
        ]));

        $this->assertTrue($schema->isString());
        $this->assertSame('string', $schema->phpType());
    }

    /**
     * @return object
     */
    private function makeJsonObject(array $json)
    {
        return json_decode(json_encode($json));
    }
}
