<?php


namespace Nurschool\Shared\Infrastructure\Symfony\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    /**
     * @inheritDoc
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sendgrid');

        // @formatter:off
        $rootNode->children()
            ->scalarNode('api_key')
            ->isRequired()
            ->end()
            ->scalarNode('host')
            ->defaultNull()
            ->end()
            ->arrayNode('curl')
            ->scalarPrototype()
            ->end()
            ->end()
        ;
        // @formatter:on

        return $treeBuilder;
    }
}