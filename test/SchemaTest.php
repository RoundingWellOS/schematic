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
        $this->assertTrue($schema->items()->isString());
    }

    public function testBoolean()
    {
        $schema = Schema::make($this->makeJsonObject([
            'type' => 'boolean',
        ]));

        $this->assertTrue($schema->isBoolean());
    }

    public function testInteger()
    {
        $schema = Schema::make($this->makeJsonObject([
            'type' => 'integer',
        ]));

        $this->assertTrue($schema->isInteger());
    }

    public function testNull()
    {
        $schema = Schema::make($this->makeJsonObject([
            'type' => 'null',
        ]));

        $this->assertTrue($schema->isNull());
    }

    public function testNumber()
    {
        $schema = Schema::make($this->makeJsonObject([
            'type' => 'number',
        ]));

        $this->assertTrue($schema->isNumber());
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
    }

    public function testString()
    {
        $schema = Schema::make($this->makeJsonObject([
            'type' => 'string',
        ]));

        $this->assertTrue($schema->isString());
    }

    /**
     * @return object
     */
    private function makeJsonObject(array $json)
    {
        return json_decode(json_encode($json));
    }
}
