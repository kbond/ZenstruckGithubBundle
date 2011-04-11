<?php

namespace Zenstruck\GithubCMSBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('zenstruck_version');

        $rootNode
            ->children()                
                ->node('user', 'variable')->defaultNull()->end()
                ->node('repo', 'variable')->defaultNull()->end()
                ->node('branch', 'variable')->defaultValue('master')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}