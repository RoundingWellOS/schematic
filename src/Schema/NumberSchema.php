<?php

namespace RoundingWell\Schematic\Schema;

use RoundingWell\Schematic\Schema;

class NumberSchema extends Schema
{
    public function phpType()
    {
        return 'float';
    }
}
