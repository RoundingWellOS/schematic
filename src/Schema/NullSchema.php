<?php

namespace RoundingWell\Schematic\Schema;

use RoundingWell\Schematic\Schema;

class NullSchema extends Schema
{
    public function phpType()
    {
        return 'null';
    }
}
