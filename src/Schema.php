<?php

namespace RoundingWell\Schematic;

abstract class Schema
{
    protected const SCHEMA_TYPES = [
        'array',
        'boolean',
        'integer',
        'null',
        'number',
        'object',
        'string'
    ];

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

        if ( ! in_array(strtolower($json->type), self::SCHEMA_TYPES)) {
            throw new \InvalidArgumentException(sprintf(
                'No schema type available for %s.',
                $json->type
            ));
        }

        $schema = 'RoundingWell\\Schematic\\Schema\\' . ucfirst($json->type) . 'Schema';

        return new $schema($json);
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
