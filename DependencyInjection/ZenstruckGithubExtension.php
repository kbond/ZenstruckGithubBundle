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

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('client.xml');

        if ($config['user']) {
            $loader->load('manager.xml');
            $container->getDefinition('zenstruck.github.manager')
                    ->setArgument(1, $config['user']);
        }

        if ($config['repo']) {
            $loader->load('filesystem.xml');
            $container->getDefinition('zenstruck.github.filesystem')                    
                    ->setArgument(0, $config['repo'])
                    ->setArgument(1, $config['branch']);
        }
    }

}
