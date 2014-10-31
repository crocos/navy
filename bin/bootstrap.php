<?php

use  Symfony\Component\Yaml\Yaml;

$kernel = require dirname(__DIR__) . '/config/bootstrap.php';

$commandResolver = $kernel->getContainer()->get('release.command_resolver');
$commandResolver->resolve(Yaml::parse(dirname(__DIR__) . '/config/command.yml'));

$shell = $kernel->getContainer()->get('shell');
$logger = $kernel->getContainer()->get('logger');

$shell->addInputCallback(function () { return 'yes' . PHP_EOL; });
$shell->addOutputCallback(function ($m) use ($logger) { $logger->info($m); });
$shell->addErrorCallback(function ($m) use ($logger) { $logger->info($m); });

return $kernel;
