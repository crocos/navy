<?php
namespace Navy;

use  Symfony\Component\Yaml\Yaml;

require dirname(__DIR__) . '/vendor/autoload.php';

$kernel = new Kernel(__DIR__);
$kernel->bootstrap();

// todo refactor command initialization
$commandResolver = $kernel->getContainer()->get('release.command_resolver');
$commandResolver->resolve(Yaml::parse(dirname(__DIR__) . '/config/command.yml'));

$shell = $kernel->getContainer()->get('shell');
$logger = $kernel->getContainer()->get('logger');

$shell->addInputCallback(function () { return 'yes' . PHP_EOL; });
$shell->addOutputCallback(function ($m) use ($logger) { $logger->info($m); });
$shell->addErrorCallback(function ($m) use ($logger) { $logger->info($m); });

return $kernel;
