<?php

namespace RoundingWell\Schematic\Schema;

use RoundingWell\Schematic\Schema;

class ObjectSchema extends Schema
{
    public function phpType()
    {
        return 'object';
    }

    /**
     * @return Schema[]
     */
    public function properties()
    {
        $props = [];

        foreach ($this->schema->properties as $name => $schema) {
            $props[$name] = $this->property($name);
        }

        return $props;
    }

    /**
     * @return Schema
     */
    public function property($name)
    {
        return Schema::make($this->schema->properties->$name);
    }

    /**
     * @return string[]
     */
    public function required()
    {
        return isset($this->schema->required) ? $this->schema->required : [];
    }

    public function isRequired($property)
    {
        return in_array($property, $this->required());
    }
}
