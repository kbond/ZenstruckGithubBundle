<?php

namespace Zenstruck\GithubCMSBundle\Github\CMS;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Manager
{
    /* @var \Github_Client */
    protected $client;
    protected $user;
    protected $repository;

    public function __construct(\Github_Client $client, $user, $repository)
    {
        $this->client = $client;
        $this->user = $user;
        $this->repository = $repository;
    }

    public function getFile($path)
    {
        $treeSHA = $this->getLatestTreeSHA();

        $blobs = $this->getBlobs($treeSHA);

        // match path
        $file = $this->matchPath($blobs, $path);

        // if not found, try with index (subdirectory)
        if (!$file)
            $file = $this->matchPath ($blobs, $path.'/index');

        // file doesn't exist
        if (!$file)
            throw new NotFoundHttpException('File not found in github repository');

        $blob = $this->client->getObjectApi()->showBlob($this->user, $this->repository, $treeSHA, $file);

        return $blob;
    }

    public function getBlobs($treeSHA)
    {   

        return $this->client->getObjectApi()->listBlobs($this->user, $this->repository, $treeSHA);
    }

    protected function matchPath($blobs, $path)
    {
        foreach ($blobs as $blob => $sha)
            if (preg_match('#^' . $path . '(\.|$)#', $blob))
                return $blob;

        return null;
    }

    protected function getLatestTreeSHA($branch = 'master')
    {
        $commit = $this->client->getCommitApi()->getBranchCommits($this->user, $this->repository, $branch);

        return $commit[0]['tree'];
    }

}
