<?php
$kernel = require __DIR__ . '/bootstrap.php';

$context = $kernel->getContainer()->get('release.command_context');

if (!isset($argv[1])) {
    echo 'Usage: php navy.php <command>', PHP_EOL;
    exit(1);
}

try {
    $ret = $context->run($argv[1]);
} catch (InvalidArgumentException $e) {
    echo $e->getMessage(), PHP_EOL;
    exit(1);
}

if ($ret === null) {
    echo 'done', PHP_EOL;
} else {
    echo $ret, PHP_EOL;
}
