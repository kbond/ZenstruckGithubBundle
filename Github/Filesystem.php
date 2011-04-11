<?php

namespace Zenstruck\GithubCMSBundle\Github;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Filesystem
{
    /* @var \Github_Client */
    protected $client;
    protected $user;
    protected $repository;
    protected $treeSHA;
    protected $branch;

    public function __construct(\Github_Client $client, $user, $repository, $branch)
    {
        $this->client = $client;
        $this->user = $user;
        $this->repository = $repository;
        $this->branch = $branch;

        $this->treeSHA = $this->getLatestTreeSHA();
    }

    /**
     * Matches a path to a github repo path, returns the blob array
     *
     * @param string $path
     * @return File blob array
     */
    public function getMatchingFile($path)
    {
        $blobs = $this->getFiles();

        // match path
        $file = $this->matchPath($blobs, $path);

        // if not found, try with index (subdirectory)
        if (!$file)
            $file = $this->matchPath ($blobs, $path.'/index');

        // file doesn't exist
        if (!$file)
            throw new NotFoundHttpException('File not found in github repository');

        $blob = $this->getFile($file);

        return $blob;
    }

    /**
     * Returns a file (exact repo path required)
     *
     * @param string $path
     * @return File blob array
     */
    public function getFile($path)
    {
        return $this->client->getObjectApi()->showBlob($this->user, $this->repository, $this->treeSHA, $path);
    }

    public function getFiles()
    {   
        return $this->client->getObjectApi()->listBlobs($this->user, $this->repository, $this->treeSHA);
    }

    protected function matchPath($blobs, $path)
    {
        foreach ($blobs as $blob => $sha)
            if (preg_match('#^' . $path . '(\.|$)#', $blob))
                return $blob;

        return null;
    }

    protected function getLatestTreeSHA()
    {
        $commit = $this->client->getCommitApi()->getBranchCommits($this->user, $this->repository, $this->branch);

        return $commit[0]['tree'];
    }

}
