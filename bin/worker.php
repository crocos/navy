<?php
$kernel = require __DIR__ . '/bootstrap.php';

$worker = $kernel->getContainer()->get('release.worker');

while (true) {
    $worker->exec();
    sleep(30);
}
