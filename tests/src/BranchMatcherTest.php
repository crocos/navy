<?php
namespace Navy;

class BranchMatcherTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->matcher = new BranchMatcher([
            'owner/repo' => [
                'master' => [
                    'workdir' => '/var/repos/owner/repo',
                ],
            ],
        ]);
    }

    public function testParseConfig()
    {
        $result = $this->matcher->parseConfig([
            'owner/repo1' => [
                'master' => null,
            ],
            'owner/repo2' => [
                'master' => [
                    'workdir' => '/var/repos/owner/repo2-master',
                ],
                'develop' => [
                    'workdir' => '/var/repos/owner/repo2-develop',
                ],
            ],
        ]);

        $expected = [
            'owner/repo1' => [
                'master' => [],
            ],
            'owner/repo2' => [
                'master' => [
                    'workdir' => '/var/repos/owner/repo2-master',
                ],
                'develop' => [
                    'workdir' => '/var/repos/owner/repo2-develop',
                ],
            ],
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider getMatchBranchData
     */
    public function testMatchBranch($expected, $repository, $branch)
    {
        $result = $this->matcher->matchBranch($repository, $branch);

        $this->assertEquals($expected, $result);
    }

    public function getMatchBranchData()
    {
        return [
            // result,  repository,         branch
            [ true,     'owner/repo',       'master' ],
            [ false,    'owner/missing',    'master' ],
            [ false,    'owner/repo',       'missing' ],
        ];
    }

    public function testFindBranchConfig()
    {
        $result = $this->matcher->findBranchConfig('owner/repo', 'master');

        $this->assertEquals([
            'workdir' => '/var/repos/owner/repo',
        ], $result);
    }

    public function testFindBranchConfigReturnsNullIfNotFound()
    {
        $result = $this->matcher->findBranchConfig('owner/repo', 'missing');

        $this->assertNull($result);
    }
}
