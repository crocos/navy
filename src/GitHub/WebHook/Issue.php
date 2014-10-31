<?php

namespace Navy\GitHub\WebHook;

class Issue
{
    protected $issue;
    protected $labels;

    public function __construct(array $issue)
    {
        $this->issue = $issue;
        if (!empty($this->issue['labels'])) {
            $this->labels = new LabelCollection($this->issue['labels']);
        }
    }

    public function getLabels()
    {
        return $this->labels;
    }

}
