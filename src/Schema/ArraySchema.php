<?php
declare(strict_types=1);

namespace RoundingWell\Schematic\Schema;

use RoundingWell\Schematic\Schema;

class ArraySchema extends Schema
{
    public function phpType(): string
    {
        if ($this->hasItems()) {
            return $this->items()->phpType() . '[]';
        }

        return 'array';
    }

    public function hasItems(): bool
    {
        return isset($this->schema->items)
            && isset($this->schema->items->type);
    }

    public function items(): ?Schema
    {
        return $this->hasItems() ? Schema::make($this->schema->items) : null;
    }
}
