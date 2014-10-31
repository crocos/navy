<?php

$loader = require dirname(__DIR__) . '/vendor/autoload.php';
$loader->add('Crocos\\', __DIR__ . '/lib');
$loader->addPsr4('Navy\\', __DIR__ . '/src');

return $loader;
