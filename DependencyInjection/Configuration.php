<?php

namespace Teneleven\Bundle\CareerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('teneleven_career');

        $rootNode
            ->children()
                ->scalarNode('from')
                    ->defaultValue('no-reply@example.com')
                ->end()
                ->arrayNode('to')
                    ->prototype('scalar')
                    ->end()
                ->end()
                ->scalarNode('subject')
                    ->defaultValue('Job Application Received')
                ->end()
                ->scalarnode('content_type')
                    ->defaultValue('text/html')
                ->end()
                ->scalarnode('template')
                    ->defaultValue('TenelevenCareerBundle:Frontend:email.html.twig')
                ->end()
            ->end()
        ;

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
