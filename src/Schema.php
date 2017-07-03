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
    public static function fromFile($path): Schema
    {
        return self::make(json_decode(file_get_contents($path)));
    }

    /**
     * @param object $json
     */
    public static function make($json): Schema
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

    public function type(): string
    {
        return $this->schema->type;
    }

    abstract public function phpType(): string;

    public function isArray(): bool
    {
        return $this->type() === 'array';
    }

    public function isBoolean(): bool
    {
        return $this->type() === 'boolean';
    }

    public function isInteger(): bool
    {
        return $this->type() === 'integer';
    }

    public function isNull(): bool
    {
        return $this->type() === 'null';
    }

    public function isNumber(): bool
    {
        return $this->type() === 'number';
    }

    public function isObject(): bool
    {
        return $this->type() === 'object';
    }

    public function isString(): bool
    {
        return $this->type() === 'string';
    }

    public function hasTitle(): bool
    {
        return isset($this->schema->title);
    }

    public function title(): string
    {
        return $this->hasTitle() ? $this->schema->title : '';
    }
}
