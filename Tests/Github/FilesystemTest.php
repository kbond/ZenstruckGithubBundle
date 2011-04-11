<?php

namespace Zenstruck\GithubCMSBundle\Tests\Github;

use Zenstruck\GithubCMSBundle\Github\Filesystem;

class ManagerTest extends \PHPUnit_Framework_TestCase
{
    protected $manager;

    public function setUp()
    {
        $client = new \Github_Client();

        $this->manager = new Filesystem($client, 'kbond', 'GithubCMSBundle-test', 'master');
    }

    public function testFindFile()
    {
        $file = $this->manager->getMatchingFile('index');

        $this->assertTrue($file['name'] == 'index.md');

        $file = $this->manager->getMatchingFile('index.md');

        $this->assertTrue($file['name'] == 'index.md');

        $file = $this->manager->getMatchingFile('subfolder/projects');

        $this->assertTrue($file['name'] == 'subfolder/projects.md');

        $file = $this->manager->getMatchingFile('htmlfile');

        $this->assertTrue($file['name'] == 'htmlfile.html');
    }

    public function testFileWithNoExtension()
    {
        $file = $this->manager->getMatchingFile('noextension');

        $this->assertTrue($file['name'] == 'noextension');
    }

    public function testSubfolderIndex()
    {
        $file = $this->manager->getMatchingFile('subfolder');

        $this->assertTrue($file['name'] == 'subfolder/index.md');
    }

    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testFileNotFound()
    {
        $file = $this->manager->getMatchingFile('projects');
    }
}
