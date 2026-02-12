<?php

declare(strict_types=1);

namespace Scaffold\CoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('scaffold_core');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('site')
                    ->children()
                        ->scalarNode('name')->isRequired()->end()
                        ->scalarNode('strapline')->isRequired()->end()
                    ->end()
                ->end()
                ->arrayNode('privacy_policy')
                    ->children()
                        ->scalarNode('last_updated')->isRequired()->end()
                        ->scalarNode('contact_email')->isRequired()->end()
                        ->scalarNode('site_url')->isRequired()->end()
                    ->end()
                ->end()
                ->arrayNode('terms_and_conditions')
                    ->children()
                        ->scalarNode('last_updated')->isRequired()->end()
                        ->scalarNode('contact_email')->isRequired()->end()
                        ->scalarNode('site_url')->isRequired()->end()
                        ->scalarNode('jurisdiction')->isRequired()->end()
                        ->scalarNode('payment_provider')->isRequired()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
