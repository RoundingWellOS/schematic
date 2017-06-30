<?php

namespace RoundingWell\Schematic\Schema;

class IntegerSchema extends NumberSchema
{
    public function phpType()
    {
        return 'int';
    }
}
