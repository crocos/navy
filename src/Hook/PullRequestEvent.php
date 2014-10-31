<?php
namespace Navy\Hook;

use Navy\GitHub\WebHook\PullRequest;

class PullRequestEvent extends Event
{
    protected $pullRequest;

    public function __construct($payload)
    {
        parent::__construct('pull_request', $payload);

        $this->pullRequest = new PullRequest($payload['pull_request']);
    }

    public function getAction()
    {
        if (!isset($this->payload['action'])) {
            return;
        }

        return $this->payload['action'];
    }

    public function getPullRequest()
    {
        return $this->pullRequest;
    }
}
