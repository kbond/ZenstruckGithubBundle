<?php

namespace Zenstruck\GithubCMSBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Symfony\Component\Config\FileLocator;

class ZenstruckGithubCMSExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $config = array();
        foreach($configs as $c) {
            $config = array_merge($config, $c);
        }

        $this->doLoadConfig($config, $container);
    }

    public function doLoadConfig($config, ContainerBuilder $container)
    {
        if (!isset($config['user']) || !isset($config['repository']))
            throw new \Exception('Must set a Github user and repository in your config.');

        $container->setParameter('github.cms.user', $config['user']);
        $container->setParameter('github.cms.repository', $config['repository']);
    }
}
