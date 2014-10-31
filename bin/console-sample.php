<?php
$kernel = require dirname(__DIR__) . '/config/bootstrap.php';

$console = $kernel->getContainer()->get('shell');
#$console->setDefaultCallback();

#$fd = fopen('php://stdin', 'r');
#stream_set_blocking($fd, 0);
#$input = function () use ($fd) { return stream_get_contents($fd); };

$result = '';

$input = function () { return 'input test' . PHP_EOL; };
$output = function ($m) { echo $m ? 'out: '. $m : ''; };
$output2 = function ($m) use (&$result) { $m ? $result .= 'out2: ' . $m : ''; };
$error = function ($m) { echo $m ? 'err: '. $m : ''; };

$console->addInputCallback($input);
$console->addOutputCallback($output);
$console->addErrorCallback($error);

$console->run('for n in `seq 1 3`; do hostname; echo $n >&2; sleep 1; done; read HOHO; echo $HOHO', $output2);
echo 'result ' . $result;
