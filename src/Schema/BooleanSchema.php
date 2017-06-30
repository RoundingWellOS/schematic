<?php

namespace RoundingWell\Schematic\Schema;

use RoundingWell\Schematic\Schema;

class BooleanSchema extends Schema
{
    public function phpType()
    {
        return 'bool';
    }
}
