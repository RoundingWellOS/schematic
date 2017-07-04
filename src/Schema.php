<?php

namespace RoundingWell\Schematic;

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
        if ( ! isset($json->type)) {
            throw new \InvalidArgumentException('Missing schema type.');
        }

        if ($json->type === 'array') {
            return new Schema\ArraySchema($json);
        }

        if ($json->type === 'boolean') {
            return new Schema\BooleanSchema($json);
        }

        if ($json->type === 'integer') {
            return new Schema\IntegerSchema($json);
        }

        if ($json->type === 'null') {
            return new Schema\NullSchema($json);
        }

        if ($json->type === 'number') {
            return new Schema\NumberSchema($json);
        }

        if ($json->type === 'object') {
            return new Schema\ObjectSchema($json);
        }

        if ($json->type === 'string') {
            return new Schema\StringSchema($json);
        }

        throw new \InvalidArgumentException(sprintf(
            "No schema type available for %s.",
            $json->type
        ));
    }

    /**
     * @var object
     */
    protected $schema;

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

    public function title(): string
    {
        return $this->schema->title ?? '';
    }
}
