<?php

require __DIR__ . '/../vendor/autoload.php';

use RoundingWell\Schematic\Schema;

$schema = Schema::fromFile(__DIR__ . '/person.json');

echo $schema->title(), "\n";
foreach ($schema->properties() as $name => $schema) {
    echo " - $name ", $schema->type();
    if ($schema->isArray()) {
        echo " of ", $schema->items()->type(), "\n";
        if ($schema->items()->isObject()) {
            foreach ($schema->items()->properties() as $name => $schema) {
                echo "   - $name ", $schema->type(), "\n";
            }
        }
    } else {
        echo "\n";
    }
}
