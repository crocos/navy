<?php
namespace Navy;

require dirname(__DIR__) . '/vendor/autoload.php';

$kernel = new Kernel(__DIR__);
$kernel->bootstrap();

return $kernel;
