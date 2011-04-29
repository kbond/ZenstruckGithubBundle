<?php

namespace Zenstruck\GithubBundle\Tests\Github;

use Zenstruck\GithubBundle\Github\GithubManager;
use Zenstruck\GithubBundle\Github\GithubFilesystem;

class GithubFilesystemTest extends \PHPUnit_Framework_TestCase
{
    protected $github_filesystem;

    public function setUp()
    {
        $client = new \Github_Client();
        
        $manager = new GithubManager($client, 'kbond');

        $this->github_filesystem = $manager->getFilesystem('GithubBundle', 'test-data');
    }

    public function testFindFile()
    {
        $file = $this->github_filesystem->getMatchingFile('index');

        $this->assertEquals($file->getPath(), 'index.md');

        $file = $this->github_filesystem->getMatchingFile('index.md');

        $this->assertEquals($file->getPath(), 'index.md');

        $file = $this->github_filesystem->getMatchingFile('subfolder/projects');

        $this->assertEquals($file->getPath(), 'subfolder/projects.md');

        $file = $this->github_filesystem->getMatchingFile('htmlfile');

        $this->assertEquals($file->getPath(), 'htmlfile.html');
    }

    public function testFileWithNoExtension()
    {
        $file = $this->github_filesystem->getMatchingFile('noextension');

        $this->assertEquals($file->getPath(), 'noextension');

    }

    public function testSubfolderIndex()
    {
        $file = $this->github_filesystem->getMatchingFile('subfolder');

        $this->assertEquals($file->getPath(), 'subfolder/index.md');
    }

    public function testFileNotFound()
    {
        $file = $this->github_filesystem->getMatchingFile('projects');
        
        $this->assertFalse($file);
    }

    public function testFileList()
    {
        $list = $this->github_filesystem->getFileList();

        $this->assertEquals(6, count($list));
        $this->assertContains('index.md', $list);

        $list = $this->github_filesystem->getFileList('subfolder');

        $this->assertEquals(2, count($list));
        $this->assertContains('subfolder/index.md', $list);
    }

    public function testGetFiles()
    {
        $files = $this->github_filesystem->getFiles();

        $this->assertEquals(6, count($files));

        $files = $this->github_filesystem->getFiles('subfolder');

        $this->assertEquals(2, count($files));
    }
}
