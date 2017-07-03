<?php
declare(strict_types=1);

namespace RoundingWell\Schematic\Schema;

class IntegerSchema extends NumberSchema
{
    public function phpType(): string
    {
        return 'int';
    }
}
