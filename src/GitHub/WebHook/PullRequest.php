<?php

namespace Navy\GitHub\WebHook;

class PullRequest
{
    protected $pullRequest;
    protected $repository;
    protected $comments;

    public function __construct(array $pullRequest)
    {
       $this->pullRequest = $pullRequest;
       $this->setRepository(new Repository($this->pullRequest['base']['repo']));
    }

    protected function fetch($url)
    {
        $token = $this->getRepository()->isPrivate() ? Config::getToken() : null;

        return (new Request($token))->request($url);
    }

    protected function fetchComments()
    {
        $url = $this->getCommentsUrl();
        $comments = $this->fetch($url);

        if ($comments) {
            $this->comments = new CommentCollection(json_decode($comments, true));
        }
    }

    protected function fetchIssue()
    {
        $url = $this->getIssueUrl();
        $issue = $this->fetch($url);

        if ($issue) {
            $this->issue = new Issue(json_decode($issue, true));
        }
    }

    protected function setRepository(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function isMerged()
    {
        return (bool) $this->pullRequest['merged'];
    }

    public function getRepository()
    {
        return $this->repository;
    }

    public function getNumber()
    {
        return $this->pullRequest['number'];
    }

    public function getTitle()
    {
        return $this->pullRequest['title'];
    }

    public function getBody()
    {
        return $this->pullRequest['body'];
    }

    public function getBaseBranch()
    {
        return $this->pullRequest['base']['ref'];
    }

    public function getMergeCommitId()
    {
        return $this->pullRequest['merge_commit_sha'];
    }

    public function getBaseCommitId()
    {
        return $this->pullRequest['base']['sha'];
    }

    public function getHeadCommitId()
    {
        return $this->pullRequest['head']['sha'];
    }

    public function getUser()
    {
        return $this->pullRequest['user']['login'];
    }

    public function getHtmlUrl()
    {
        return $this->pullRequest['html_url'];
    }

    public function getMergedUser()
    {
        return $this->pullRequest['merged_by']['login'];
    }

    public function getIssueUrl()
    {
        return $this->pullRequest['issue_url'];
    }

    public function getIssue()
    {
        if (empty($this->issue)) {
            $this->fetchIssue();
        }

        return $this->issue;
    }

    public function getCommentsUrl()
    {
        return $this->pullRequest['comments_url'];
    }

    public function getComments()
    {
        if (empty($this->comments)) {
            $this->fetchComments();
        }

        return $this->comments;
    }
}
