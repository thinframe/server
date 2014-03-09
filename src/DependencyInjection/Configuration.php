<?php

/**
 * @author    Sorin Badea <sorin.badea91@gmail.com>
 * @license   MIT license (see the license file in the root directory)
 */

namespace ThinFrame\Server\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration
 *
 * @package ThinFrame\Server\DependencyInjection
 * @since   0.3
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('server');


        $rootNode
            ->children()
            ->scalarNode('host')->defaultValue('127.0.0.1')->end()
            ->scalarNode('port')->defaultValue('1337')->end()
            ->end();

        return $treeBuilder;
    }
}
