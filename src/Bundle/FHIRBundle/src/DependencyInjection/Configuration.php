<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
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
        /** @var ArrayNodeDefinition $rootNode */
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
                ->arrayNode('serialization')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('metadata_cache_pool')
                            ->defaultValue('cache.app')
                            ->info('PSR-6 cache pool service ID for property metadata. Set to null to disable persistent caching.')
                        ->end()
                        ->booleanNode('enable_cache_warmer')
                            ->defaultFalse()
                            ->info('Pre-populate the metadata cache during cache:warmup. Only takes effect when metadata_cache_pool is set.')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('ig')
                    ->addDefaultsIfNotSet()
                    ->info('Implementation Guide (IG) code generation settings.')
                    ->children()
                        ->arrayNode('packages')
                            ->info('IG packages to generate, in dependency order (e.g. au.base before au.core). Accepts "name" or "name#version" format.')
                            ->scalarPrototype()->end()
                            ->defaultValue([])
                        ->end()
                        ->booleanNode('offline')
                            ->defaultFalse()
                            ->info('Use only locally cached packages; skip network downloads when generating IG classes.')
                        ->end()
                        ->scalarNode('output_directory')
                            ->defaultNull()
                            ->info('Root directory for generated IG PHP classes. Defaults to output_directory/IG when null. Must match the PSR-4 source root for ig.namespace.')
                        ->end()
                        ->scalarNode('namespace')
                            ->defaultNull()
                            ->info('Root PHP namespace for generated IG classes (e.g. "App\\FHIR\\IG"). Must match the PSR-4 autoload mapping in composer.json. Defaults to the library internal namespace when null.')
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
