<?php

namespace Zenstruck\GithubCMSBundle\Tests\Github;

use Zenstruck\GithubCMSBundle\Github\Filesystem;

class ManagerTest extends \PHPUnit_Framework_TestCase
{
    protected $manager;

    public function setUp()
    {
        $client = new \Github_Client();

        $this->manager = new Filesystem($client, 'kbond', 'GithubCMSBundle', 'test-data');
    }

    public function testFindFile()
    {
        $file = $this->manager->getMatchingFile('index');

        $this->assertEquals($file->getPath(), 'index.md');

        $file = $this->manager->getMatchingFile('index.md');

        $this->assertEquals($file->getPath(), 'index.md');

        $file = $this->manager->getMatchingFile('subfolder/projects');

        $this->assertEquals($file->getPath(), 'subfolder/projects.md');

        $file = $this->manager->getMatchingFile('htmlfile');

        $this->assertEquals($file->getPath(), 'htmlfile.html');
    }

    public function testFileWithNoExtension()
    {
        $file = $this->manager->getMatchingFile('noextension');

        $this->assertEquals($file->getPath(), 'noextension');

    }

    public function testSubfolderIndex()
    {
        $file = $this->manager->getMatchingFile('subfolder');

        $this->assertEquals($file->getPath(), 'subfolder/index.md');
    }

    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testFileNotFound()
    {
        $file = $this->manager->getMatchingFile('projects');
    }

    public function testFileList()
    {
        $files = $this->manager->getFileList();

        $this->assertArrayHasKey('index.md', $files);
    }
}
