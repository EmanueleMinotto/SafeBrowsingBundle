<?php

namespace EmanueleMinotto\SafeBrowsingBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * {@inheritdoc}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('safe_browsing');

        $rootNode
            ->children()
                ->arrayNode('configs')
                    ->children()
                        ->scalarNode('client')
                            ->defaultValue('app')
                            ->info('The client name')
                        ->end()
                        ->scalarNode('appver')
                            ->defaultValue('1.0.0')
                            ->info('The version of the client')
                        ->end()
                        ->scalarNode('key')
                            ->info('API key')
                            ->isRequired()
                        ->end()
                        ->enumNode('pver')
                            ->defaultValue(3.1)
                            ->info('The protocol version supported')
                            ->values([3.0, 3.1])
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('cache')
                    ->defaultNull()
                    ->info('Caching service')
                ->end()
                ->scalarNode('http_client')
                    ->defaultNull()
                    ->info('HTTP client service')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
