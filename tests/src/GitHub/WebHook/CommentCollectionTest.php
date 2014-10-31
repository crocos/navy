<?php
namespace Navy\GitHub\WebHook;

class CommentCollectionTest extends \PHPUnit_Framework_TestCase
{
    protected $commentCollection;

    public function setUp()
    {
        $this->commentCollection = new CommentCollection([
            [ 'body' => 'test1' ],
            [ 'body' => 'test2' ],
            [ 'body' => 'test3' ],
        ]);
    }

    public function tearDown()
    {
    }

    public function testCurrentValue()
    {
        $this->assertEquals('test1', $this->commentCollection->current()->getBody());
    }

    public function testSkipPointer()
    {
        $this->commentCollection->next();
        $this->assertEquals('test2', $this->commentCollection->current()->getBody());
        $this->commentCollection->next();
        $this->assertEquals('test3', $this->commentCollection->current()->getBody());

        $this->commentCollection->rewind();
        $this->assertEquals('test1', $this->commentCollection->current()->getBody());
    }

    public function testLoop()
    {
        $key = 0;
        foreach ($this->commentCollection as $value) {
            $key++;
            $this->assertEquals('test'.$key, $value->getBody());
        }
    }

}
