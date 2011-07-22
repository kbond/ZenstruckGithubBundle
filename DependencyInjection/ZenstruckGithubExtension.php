<?php

namespace Zenstruck\Bundle\GithubBundle\DependencyInjection;

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
                    ->replaceArgument(1, $config['user'])
                    ->replaceArgument(2, $config['token']);
        }

        if ($config['repo']) {
            $loader->load('filesystem.xml');
            $container->getDefinition('zenstruck.github.filesystem')
                    ->replaceArgument(0, $config['repo'])
                    ->replaceArgument(1, $config['branch']);
        }
    }

}
