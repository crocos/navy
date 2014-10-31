<?php

namespace Navy;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }

    public function tearDown()
    {
    }

    public function testGetRequest()
    {
        $data = [ 'foo' => 'aa', 'bar' => 'bb', 'baz' => 'cc' ];
        $GLOBALS['_SERVER']['REQUEST_METHOD'] = 'GET';
        $GLOBALS['_SERVER']['REQUEST_URI'] = '/hoge';
        $GLOBALS['_GET'] = $data;

        $request = new Request();

        $this->assertEquals(Request::METHOD_GET, $request->getMethod());
        $this->assertEquals('hoge', $request->getEvent());
        $this->assertEquals($data, $request->getPayload());
    }

    public function testPostRequestJson()
    {
        $data = [ 'foo' => 'aa', 'bar' => 'bb', 'baz' => 'cc' ];
        $GLOBALS['_SERVER']['REQUEST_METHOD'] = 'POST';
        $GLOBALS['_SERVER']['CONTENT_TYPE'] = 'application/json';
        $GLOBALS['_SERVER']['HTTP_X_GITHUB_EVENT'] = 'pull_request';
        $GLOBALS['_SERVER']['HTTP_X_GITHUB_DELIVERY'] = 'aaaaaabbbccccc';

        $request = new Request();

        $this->assertEquals(Request::METHOD_POST, $request->getMethod());
        $this->assertEquals('pull_request', $request->getEvent());
        $this->assertEquals('aaaaaabbbccccc', $request->getDelivery());
        $this->assertEquals($data, $request->getPayload());
    }

    public function testPostRequestForm()
    {
        $data = [ 'foo' => 'aa', 'bar' => 'bb', 'baz' => 'cc' ];
        $GLOBALS['_SERVER']['REQUEST_METHOD'] = 'POST';
        $GLOBALS['_SERVER']['CONTENT_TYPE'] = 'application/x-www-form-urlencoded';
        $GLOBALS['_SERVER']['HTTP_X_GITHUB_EVENT'] = 'pull_request';
        $GLOBALS['_SERVER']['HTTP_X_GITHUB_DELIVERY'] = 'aaaaaabbbccccc';
        $GLOBALS['_POST']['payload'] = json_encode($data);

        $request = new Request();

        $this->assertEquals(Request::METHOD_POST, $request->getMethod());
        $this->assertEquals('pull_request', $request->getEvent());
        $this->assertEquals('aaaaaabbbccccc', $request->getDelivery());
        $this->assertEquals($data, $request->getPayload());
    }

}

// override json test.
function fread($input)
{
    return json_encode([ 'foo' => 'aa', 'bar' => 'bb', 'baz' => 'cc' ]);
}
