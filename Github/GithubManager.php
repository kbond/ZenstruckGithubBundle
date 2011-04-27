<?php

namespace Zenstruck\GithubBundle\Github;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class GithubManager
{
    protected $client;
    protected $user;
    
    public function __construct(\Github_Client $client, $user)
    {
        $this->client = $client;
        $this->user = $user;
    }
    
    /**
     * @return \Github_Client
     */
    public function getClient()
    {
        return $this->client;
    }
    
    public function getFilesystem($repo, $branch = 'master')
    {
        return new GithubFilesystem($this, $this->user, $repo, $branch);
    }
}
