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
        $file_list = $this->getFileList();

        // match path
        $filesname = $this->matchPath($file_list, $path);

        // if not found, try with index (subdirectory)
        if (!$filesname)
            $filesname = $this->matchPath($file_list, $path . '/index');

        // file doesn't exist
        if (!$filesname)
            throw new NotFoundHttpException('File not found in github repository');

        $file = $this->getFile($filesname);

        return $file;
    }

    /**
     * Returns a file (exact repo path required)
     *
     * @param string $path
     * @return File
     */
    public function getFile($path)
    {
        $blob = $this->client->getObjectApi()->showBlob($this->user, $this->repository, $this->treeSHA, $path);
        $info = $this->client->getCommitApi()->getFileCommits($this->user, $this->repository, $this->branch, $path);

        $file = new File();
        $file->setPath($blob['name']);
        $file->setContent($blob['data']);
        $file->setAuthor($info[0]['author']['name']);
        $file->setUpdated($info[0]['committed_date']);

        $file->setCreated($info[count($info) - 1]['committed_date']);

        return $file;
    }

    /**
     * Returns an array of File objects
     * **CAUTION** Many API calls on large repos
     *
     * @param string $path - the directory path to look in
     */
    public function getFiles($path = '')
    {
        $file_list = $this->getFileList($path);

        $files = array();

        // hyrdate
        foreach ($file_list as $filename)
            $files[] = $this->getFile($filename);

        return $files;
    }

    /**
     * Returns an array of file paths in repo
     *
     * @param string $path - the directory path to look in
     * @return array - the list of files ('path' => 'id')
     */
    public function getFileList($path = '')
    {
        $blobs = $this->client->getObjectApi()->listBlobs($this->user, $this->repository, $this->treeSHA);

        if (!$path)
            return array_keys($blobs);

        $files = array();

        foreach ($blobs as $key => $id)
        {
            if (preg_match('#^'.$path.'#', $key))
                $files[] = $key;
        }

        return $files;
    }

    protected function matchPath($files, $path)
    {
        foreach ($files as $file)
            if (preg_match('#^' . $path . '(\.|$)#', $file))
                return $file;

        return null;
    }

    protected function getLatestTreeSHA()
    {
        $commit = $this->client->getCommitApi()->getBranchCommits($this->user, $this->repository, $this->branch);

        return $commit[0]['tree'];
    }

}
