<?php

namespace Zenstruck\Bundle\GithubBundle\Github;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class GithubManager
{
    protected $client;
    protected $user;

    public function __construct(\Github_Client $client, $user, $token = null)
    {
        $this->client = $client;
        $this->user = $user;

        if ($token) {
            $this->client->authenticate($user, $token, \Github_Client::AUTH_HTTP_TOKEN);
        }
    }

    public function getUser()
    {
        return $this->user;
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

    public function getRepoInfo($repo)
    {
        return $this->client->getRepoApi()->show($this->user, $repo);
    }

    public function getRepoTags($repo)
    {
        return $this->client->getRepoApi()->getRepoTags($this->user, $repo);
    }
}
