<?php

namespace Navy\GitHub\WebHook;

class LabelCollection implements \Iterator
{
    protected $position;
    protected $labels;

    public function __construct(array $labels)
    {
        $this->labels = array_map(function ($v) { return new Label($v); }, $labels);
        $this->rewind();
    }

    public function current()
    {
        return $this->labels[$this->position]->getName();
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
        return isset($this->labels[$this->position]);
    }
}
