<?php
namespace Navy\GitHub\WebHook;

class PullRequestTest extends \PHPUnit_Framework_TestCase
{
    protected $pullRequest;

    public function setUp()
    {
    }

    public function tearDown()
    {
    }

    /**
     * @dataProvider responseMergedData
     */
    public function testGetComment($response)
    {
        $pullRequest = new PullRequest($response);

        $this->markTestIncomplete('test incomplate');
    }

    public function responseMergedData()
    {
        $response = json_decode(
            file_get_contents(__DIR__.'/_files/merged_response.json')
            ,true
        );

        return [
            [ $response['pull_request'] ],
        ];
    }

    protected function responseUnmergedData()
    {
        $response = json_decode(
            file_get_contents(__DIR__.'/_files/unmerged_response.json')
            ,true
        );

        return [
            [ $response['pull_request'] ],
        ];
    }
}
