<?php
namespace Navy;

class BranchMatcher
{
    protected $config;
    protected $cache = [];

    public function __construct(array $config)
    {
        // navy/navy:
        //   develop:
        //     workdir: /var/deploy/navy
        $this->config = $this->parseConfig($config);
    }

    public function parseConfig(array $config)
    {
        foreach ($config as $repositoryName => $repositoryConfig) {
            foreach ($repositoryConfig as $branchName => $branchConfig) {
                $config[$repositoryName][$branchName] = (array) $branchConfig;
            }
        }

        return $config;
    }

    public function matchBranch($repositoryName, $branchName)
    {
        if (isset($this->config[$repositoryName][$branchName])) {
            return true;
        }

        return false;
    }

    public function findBranchConfig($repositoryName, $branchName)
    {
        if (isset($this->config[$repositoryName][$branchName])) {
            return $this->config[$repositoryName][$branchName];
        }

        return;
    }
}
