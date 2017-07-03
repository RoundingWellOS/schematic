<?php
declare(strict_types=1);

namespace RoundingWell\Schematic\Schema;

use RoundingWell\Schematic\Schema;

class NumberSchema extends Schema
{
    public function phpType(): string
    {
        return 'float';
    }
}
