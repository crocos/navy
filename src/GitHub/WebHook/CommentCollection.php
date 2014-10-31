<?php

namespace Navy\GitHub\WebHook;

class CommentCollection implements \Iterator
{
    protected $position;
    protected $comments;

    public function __construct(array $comments)
    {
        $this->comments = array_map(function ($v) {
            return ($v instanceof Comment) ? $v : new Comment($v);
        }, $comments);

        $this->rewind();
    }

    public function current()
    {
        return $this->comments[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        $this->position++;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function valid()
    {
        return isset($this->comments[$this->position]);
    }
}
