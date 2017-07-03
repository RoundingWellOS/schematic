<?php
declare(strict_types=1);

namespace RoundingWell\Schematic\Schema;

use RoundingWell\Schematic\Schema;

class ObjectSchema extends Schema
{
    public function phpType(): string
    {
        return 'object';
    }

    public function property($name): Schema
    {
        return Schema::make($this->schema->properties->$name);
    }

    /**
     * @return Schema[]
     */
    public function properties(): array
    {
        $props = [];

        foreach ($this->schema->properties as $name => $schema) {
            $props[$name] = $this->property($name);
        }

        return $props;
    }

    /**
     * @return string[]
     */
    public function required(): array
    {
        return isset($this->schema->required) ? $this->schema->required : [];
    }

    public function isRequired($property): bool
    {
        return in_array($property, $this->required());
    }
}
