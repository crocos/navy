<?php
namespace Navy;

use Phake;

class KernelTest extends \PHPUnit_Framework_TestCase
{
    protected $kernel;

    protected function setUp()
    {
        $this->kernel = Phake::partialMock(Kernel::class, dirname(__DIR__) . '/config');
    }

    protected function tearDown()
    {
    }

    public function testBootstrap()
    {
        $this->kernel->bootstrap();

        $plugins = $this->kernel->getPlugins();
        $this->assertEquals(1 + 2, count($plugins));
        $this->assertTrue(isset($plugins['navy']));
        $this->assertTrue(isset($plugins['hookaware']));
        $this->assertTrue(isset($plugins['empty']));

        $this->assertEquals($this->kernel, $this->kernel->getContainer()->get('kernel'));
    }

}
