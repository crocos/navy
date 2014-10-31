<?php
namespace Navy;

$kernel = require dirname(__DIR__) . '/config/bootstrap.php';

$handler = $kernel->getContainer()->get('hook_handler');

$handler->handle(new Request())->send();
