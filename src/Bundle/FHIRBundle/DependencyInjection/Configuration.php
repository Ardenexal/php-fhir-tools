<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * FHIR Bundle Configuration.
 *
 * Defines the configuration tree for FHIR bundle settings including
 * output directories, caching, validation options, and FHIR versions.
 *
 * @author Ardenexal FHIRTools Team
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('fhir');
        $rootNode    = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('output_directory')
                    ->defaultValue('%kernel.project_dir%/output')
                    ->info('Directory where generated FHIR classes will be stored')
                ->end()
                ->scalarNode('cache_directory')
                    ->defaultValue('%kernel.cache_dir%/fhir')
                    ->info('Directory for FHIR package cache and metadata')
                ->end()
                ->scalarNode('default_version')
                    ->defaultValue('R4B')
                    ->info('Default FHIR version to use when not specified')
                    ->validate()
                        ->ifNotInArray(['R4', 'R4B', 'R5'])
                        ->thenInvalid('Invalid FHIR version %s. Supported versions: R4, R4B, R5')
                    ->end()
                ->end()
                ->arrayNode('validation')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')
                            ->defaultTrue()
                            ->info('Enable FHIR validation during code generation')
                        ->end()
                        ->booleanNode('strict_mode')
                            ->defaultFalse()
                            ->info('Enable strict validation mode (fail on warnings)')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('packages')
                    ->info('FHIR package configuration')
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('version')
                                ->isRequired()
                                ->info('Package version to use')
                            ->end()
                            ->scalarNode('url')
                                ->info('Custom package URL (optional)')
                            ->end()
                            ->booleanNode('auto_update')
                                ->defaultFalse()
                                ->info('Automatically update package to latest version')
                            ->end()
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
