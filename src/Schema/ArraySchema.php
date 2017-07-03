<?php
declare(strict_types=1);

namespace RoundingWell\Schematic\Schema;

use RoundingWell\Schematic\Schema;

class ArraySchema extends Schema
{
    public function phpType(): string
    {
        return $this->items()->phpType() . '[]';
    }

    public function items(): Schema
    {
        return Schema::make($this->schema->items);
    }
}
