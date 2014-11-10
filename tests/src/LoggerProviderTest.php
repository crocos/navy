<?php
namespace Navy;

use Psr\Log\LoggerInterface;

class LoggerProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $provider = new LoggerProvider([
            [
                'type'         => 'fingers_crossed',
                'action_level' => 'error',
                'handler'      => [
                    'type'   => 'stream',
                    'stream' => __DIR__.'/test.log',
                    'level'  => 'debug',
                ],
            ]
        ]);

        $logger = $provider->get();

        $this->assertInstanceOf(LoggerInterface::class, $logger);
    }
}
