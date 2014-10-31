<?php
namespace Navy\Hook;

use Navy\Fixtures\HookAwarePlugin\Hook as PluginHook;
use Navy\Request;
use Phake;

class HookHandlerTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $hooks = [
            'foo' => [new PluginHook\FooHook()],
            'bar' => [new PluginHook\BarHook()],
        ];

        $hooksProvider = Phake::mock(HooksProviderInterface::class);
        Phake::when($hooksProvider)->getHooks()->thenReturn($hooks);

        $this->handler = Phake::partialMock(HookHandler::class, $hooksProvider, new EventFactory());
    }

    public function testProcessDoesNotSupportGet()
    {
        $request = Phake::mock(Request::class);
        Phake::when($request)->getMethod()->thenReturn(Request::METHOD_GET);

        $response = $this->handler->handle($request);
        $result = json_decode($response->getContent(), true);

        $this->assertEquals(405, $response->getStatusCode());
        $this->assertFalse($result['result']);
    }

    public function testProcessPost()
    {
        $request = Phake::mock(Request::class);
        Phake::when($request)->getMethod()->thenReturn(Request::METHOD_POST);
        Phake::when($request)->getEvent()->thenReturn('foo');
        Phake::when($request)->getPayload()->thenReturn(['say' => 'hello']);

        $response = $this->handler->handle($request);
        $result = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($result['result']);
    }

    public function testProcessShouldRespondWithExeption()
    {
        $request = Phake::mock(Request::class);
        Phake::when($request)->getMethod()->thenReturn(Request::METHOD_POST);
        Phake::when($request)->getEvent()->thenReturn('foo');
        Phake::when($request)->getPayload()->thenReturn(['say' => 'hello']);

        Phake::when($this->handler)->handlePost->thenThrow(new \Exception());

        $response = $this->handler->handle($request);
        $result = json_decode($response->getContent(), true);

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertFalse($result['result']);
    }
}
