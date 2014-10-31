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

    public function testConstructor()
    {
        $this->assertEquals($this->kernel, $this->kernel->getContainer()->get('kernel'));
    }

    public function testBootstrap()
    {
        $this->kernel->bootstrap();

        Phake::verify($this->kernel, Phake::times(2 + 2))->loadConfig;  // 2 core + 2 plugins
        Phake::verify($this->kernel)->loadPlugins();

        $plugins = $this->kernel->getPlugins();
        $this->assertEquals(2, count($plugins));
        $this->assertTrue(isset($plugins['hookaware']));
        $this->assertTrue(isset($plugins['empty']));

        $hooks = $this->kernel->getHooks();
        $this->assertEquals(2, count($hooks));
    }

}
