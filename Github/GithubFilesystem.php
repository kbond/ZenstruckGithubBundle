<?php

namespace Zenstruck\GithubBundle\Github;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GithubFilesystem
{
    /* @var GithubManager */
    protected $manager;
    protected $user;
    protected $repository;
    protected $treeSHA;
    protected $branch;

    public function __construct(GithubManager $manager, $user, $repository, $branch = 'master')
    {
        $this->manager = $manager;
        $this->user = $user;
        $this->repository = $repository;
        $this->branch = $branch;

        $this->treeSHA = $this->getLatestTreeSHA();
    }
    
    public function getUser()
    {
        return $this->user;
    }


    /**
     * @return GithubManager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * Matches a path to a github repo path, returns the blob array
     *
     * @param string $path
     * @return GithubFile blob array
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
     * @return GithubFile
     */
    public function getFile($path)
    {
        $blob = $this->manager->getClient()->getObjectApi()->showBlob($this->user, $this->repository, $this->treeSHA, $path);
        $info = $this->manager->getClient()->getCommitApi()->getFileCommits($this->user, $this->repository, $this->branch, $path);

        $file = new GithubFile();
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
        $blobs = $this->manager->getClient()->getObjectApi()->listBlobs($this->user, $this->repository, $this->treeSHA);

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
        $commit = $this->manager->getClient()->getCommitApi()->getBranchCommits($this->user, $this->repository, $this->branch);

        return $commit[0]['tree'];
    }

}
