<?php
namespace Navy;

$kernel = require dirname(__DIR__) . '/config/bootstrap.php';

$reader = $kernel->getContainer()->get('releaselog.reader');

$filter = isset($_GET['filter']) ? $_GET['filter'] : null;
switch ($filter) {
    case 'detail':
        $log = $reader->getRecentDetail();
        break;
    case 'dev':
        $log = $reader->getRecentDev();
        break;
    case null:
        $log = $reader->getRecentGeneral();
        break;
    default:
        $log = $reader->getRecentFilter($filter);
        break;
}

header('Content-Type: text/plain; charset=utf-8');
echo $log;
