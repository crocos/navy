<?php
namespace Navy\GitHub\WebHook;

class CommentTest extends \PHPUnit_Framework_TestCase
{
    protected $comment;

    public function setUp()
    {
        $this->comment = new Comment([
            'body' => 'this is test comment',
            'user' => [
                'login' => 'yudoufu',
            ],
        ]);
    }

    public function tearDown()
    {
    }

    public function testGetBody()
    {
        $this->assertEquals('this is test comment', $this->comment->getBody());
    }

    public function testGetUser()
    {
        $this->assertEquals('yudoufu', $this->comment->getUser());
    }

}
