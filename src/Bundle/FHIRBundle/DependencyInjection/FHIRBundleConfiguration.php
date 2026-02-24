<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * FHIR Bundle Configuration tree builder
 *
 * @author Ardenexal
 */
class FHIRBundleConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('fhir');
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode    = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('generation')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('output_directory')
                            ->defaultValue('%kernel.project_dir%/src/FHIR')
                            ->info('Directory where generated FHIR classes will be stored')
                        ->end()
                        ->scalarNode('base_namespace')
                            ->defaultValue('App\\FHIR')
                            ->info('Base namespace for generated FHIR classes')
                        ->end()
                        ->booleanNode('generate_tests')
                            ->defaultFalse()
                            ->info('Whether to generate unit tests for FHIR classes')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('serialization')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('strict_validation')
                            ->defaultTrue()
                            ->info('Enable strict FHIR validation during serialization')
                        ->end()
                        ->booleanNode('cache_metadata')
                            ->defaultTrue()
                            ->info('Cache FHIR metadata for improved performance')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('path')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->integerNode('cache_size')
                            ->defaultValue(100)
                            ->min(10)
                            ->max(10000)
                            ->info('Maximum number of FHIRPath expressions to cache')
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
