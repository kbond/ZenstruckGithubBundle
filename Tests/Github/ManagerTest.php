<?php

namespace Zenstruck\GithubCMSBundle\Tests\Github;

use Zenstruck\GithubCMSBundle\Github\Manager;

class ManagerTest extends \PHPUnit_Framework_TestCase
{
    protected $manager;

    public function setUp()
    {
        $client = new \Github_Client();

        $this->manager = new Manager($client, 'kbond', 'GithubCMSBundle-test');
    }

    public function testFindFile()
    {
        $file = $this->manager->getFile('index');

        $this->assertTrue($file['name'] == 'index.md');

        $file = $this->manager->getFile('index.md');

        $this->assertTrue($file['name'] == 'index.md');

        $file = $this->manager->getFile('subfolder/projects');

        $this->assertTrue($file['name'] == 'subfolder/projects.md');

        $file = $this->manager->getFile('htmlfile');

        $this->assertTrue($file['name'] == 'htmlfile.html');
    }

    public function testFileWithNoExtension()
    {
        $file = $this->manager->getFile('noextension');

        $this->assertTrue($file['name'] == 'noextension');
    }

    public function testSubfolderIndex()
    {
        $file = $this->manager->getFile('subfolder');

        $this->assertTrue($file['name'] == 'subfolder/index.md');
    }

    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testFileNotFound()
    {
        $file = $this->manager->getFile('projects');
    }
}
