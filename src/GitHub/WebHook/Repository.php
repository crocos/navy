<?php

namespace Navy\GitHub\WebHook;

class Repository
{
    const TYPE_ORGANZATION = 'Organization';
    const TYPE_USER = 'User';

    protected $name;
    protected $owner;
    protected $type;
    protected $defaultBranch;
    protected $private;

    public function __construct(array $repository)
    {
        $this->name = $repository['name'];
        $this->owner = $repository['owner']['login'];
        $this->type = $repository['owner']['type'];
        $this->defaultBranch = $repository['default_branch'];
        $this->private = $repository['private'];
    }

    public function getName()
    {
        return $this->name;
    }

    public function getOwner()
    {
        return $this->owner;
    }

    public function getFullName()
    {
        return $this->owner . '/' . $this->name;
    }

    public function getDefaultBranch()
    {
        return $this->defaultBranch;
    }

    public function isOrganization()
    {
        return ($this->type === static::TYPE_ORGANZATION);
    }

    public function isUser()
    {
        return ($this->type === static::TYPE_USER);
    }

    public function isPrivate()
    {
        return (bool) $this->private;
    }

}
