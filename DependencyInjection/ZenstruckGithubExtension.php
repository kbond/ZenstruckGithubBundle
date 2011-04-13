<?php

namespace Zenstruck\GithubBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;

class ZenstruckGithubExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('github.xml');

        if (!$config['user'] || !$config['repo'])
            throw new \Exception('User and Repo must be set in your configuration');

        $container->getDefinition('zenstruck.github.filesystem')
                ->setArgument(1, $config['user'])
                ->setArgument(2, $config['repo'])
                ->setArgument(3, $config['branch']);
    }
}
