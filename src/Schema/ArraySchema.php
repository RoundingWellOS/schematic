<?php

namespace RoundingWell\Schematic\Schema;

use RoundingWell\Schematic\Schema;

class ArraySchema extends Schema
{
    public function phpType()
    {
        return $this->items()->phpType() . '[]';
    }

    /**
     * @return Schema
     */
    public function items()
    {
        return Schema::make($this->schema->items);
    }
}
