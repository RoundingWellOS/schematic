<?php

require __DIR__ . '/../vendor/autoload.php';

use RoundingWell\Schematic\Schema;
use RoundingWell\Schematic\Generator;
use RoundingWell\Schematic\Writer;

$schema = Schema::fromFile(__DIR__ . '/car.json');
$generator = new Generator();

$classes = $generator->generate($schema, 'Acme\Model\Car', 'Acme\Model');
$paths = $generator->write($classes, __DIR__ . '/generated', 'Acme\Model');

echo "Generated the following files: \n";
print_r($paths);
