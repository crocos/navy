<?php

namespace Navy\GitHub\WebHook;

class Comment
{
    protected $comment;

    public function __construct(array $comment)
    {
        $this->comment = $comment;
    }

    public function getBody()
    {
        return $this->comment['body'];
    }

    public function getUser()
    {
        return $this->comment['user']['login'];
    }
}
