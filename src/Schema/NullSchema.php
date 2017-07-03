<?php
declare(strict_types=1);

namespace RoundingWell\Schematic\Schema;

use RoundingWell\Schematic\Schema;

class NullSchema extends Schema
{
    public function phpType(): string
    {
        return 'null';
    }
}
