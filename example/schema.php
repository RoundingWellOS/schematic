<?php

require __DIR__ . '/../vendor/autoload.php';

use RoundingWell\Schematic\Schema;

$schema = Schema::fromFile(__DIR__ . '/person.json');

echo $schema->title(), "\n";
foreach ($schema->properties() as $name => $schema) {
    echo " - $name ", $schema->phpType(), "\n";
    if ($schema->isArray()) {
        if ($schema->items()->isObject()) {
            foreach ($schema->items()->properties() as $name => $schema) {
                echo "   - $name ", $schema->phpType(), "\n";
            }
        }
    } elseif ($schema->isObject()) {
        foreach ($schema->properties() as $name => $schema) {
            echo "   - $name ", $schema->phpType(), "\n";
        }
    }
}
