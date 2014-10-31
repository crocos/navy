<?php

namespace Navy\GitHub\WebHook;

class Label
{
    protected $label;

    public function __construct(array $label)
    {
        $this->label = $label;
    }

    public function getName()
    {
        return $this->label['name'];
    }

    public function getColor()
    {
        return $this->label['color'];
    }

    public function getUrl()
    {
        return $this->label['url'];
    }
}
