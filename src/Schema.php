<?php

namespace RoundingWell\Schematic;

use RoundingWell\Schematic\Schema\ArraySchema;
use RoundingWell\Schematic\Schema\BooleanSchema;
use RoundingWell\Schematic\Schema\IntegerSchema;
use RoundingWell\Schematic\Schema\NullSchema;
use RoundingWell\Schematic\Schema\NumberSchema;
use RoundingWell\Schematic\Schema\ObjectSchema;
use RoundingWell\Schematic\Schema\StringSchema;

abstract class Schema
{
    /**
     * @param string $path
     * @return static
     */
    public static function fromFile($path)
    {
        return self::make(json_decode(file_get_contents($path)));
    }

    /**
     * @param object $json
     * @return static
     */
    public static function make($json)
    {
        if ($json->type === 'array') {
            return new ArraySchema($json);
        }

        if ($json->type === 'boolean') {
            return new BooleanSchema($json);
        }

        if ($json->type === 'integer') {
            return new IntegerSchema($json);
        }

        if ($json->type === 'null') {
            return new NullSchema($json);
        }

        if ($json->type === 'number') {
            return new NumberSchema($json);
        }

        if ($json->type === 'object') {
            return new ObjectSchema($json);
        }

        if ($json->type === 'string') {
            return new StringSchema($json);
        }

        // @codeCoverageIgnoreStart
        throw new \InvalidArgumentException(sprintf(
            "No schema type available for %s",
            $schema->type
        ));
        // @codeCoverageIgnoreEnd
    }

    /**
     * @var object
     */
    protected $schema;

    /**
     * @var boolean
     */
    protected $isRequired = false;

    public function __construct($schema)
    {
        $this->schema = $schema;
    }

    /**
     * @return string
     */
    public function type()
    {
        return $this->schema->type;
    }

    public function isArray()
    {
        return $this->type() === 'array';
    }

    public function isBoolean()
    {
        return $this->type() === 'boolean';
    }

    public function isInteger()
    {
        return $this->type() === 'integer';
    }

    public function isNull()
    {
        return $this->type() === 'null';
    }

    public function isNumber()
    {
        return $this->type() === 'number';
    }

    public function isObject()
    {
        return $this->type() === 'object';
    }

    public function isString()
    {
        return $this->type() === 'string';
    }

    public function hasTitle()
    {
        return isset($this->schema->title);
    }

    /**
     * @return string|null
     */
    public function title()
    {
        return $this->hasTitle() ? $this->schema->title : null;
    }
}
