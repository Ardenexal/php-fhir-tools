<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\DependencyInjection;

use Ardenexal\FHIRTools\Bundle\FHIRBundle\CacheWarmer\FHIRMetadataCacheWarmer;
use Ardenexal\FHIRTools\Bundle\FHIRBundle\Compatibility\SymfonyVersionHelper;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\PropertyMetadataProvider;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

/**
 * FHIR Bundle Extension for dependency injection configuration.
 *
 * Handles the semantic configuration for FHIR services and loads
 * service definitions from YAML files.
 *
 * @author Ardenexal FHIRTools Team
 */
class FHIRExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        // Set configuration parameters
        $container->setParameter('fhir.output_directory', $config['output_directory']);
        $container->setParameter('fhir.cache_directory', $config['cache_directory']);
        $container->setParameter('fhir.default_version', $config['default_version']);
        $container->setParameter('fhir.validation_enabled', $config['validation']['enabled']);
        $container->setParameter('fhir.validation_strict_mode', $config['validation']['strict_mode']);
        $container->setParameter('fhir.path.cache_size', $config['path']['cache_size']);

        // Set version-specific parameters
        $versionConfig = SymfonyVersionHelper::getVersionSpecificServiceConfig();
        foreach ($versionConfig as $key => $value) {
            $container->setParameter('fhir.' . $key, $value);
        }

        // Set Symfony version information
        $container->setParameter('fhir.symfony_version', SymfonyVersionHelper::getSymfonyVersion());
        $container->setParameter('fhir.is_symfony_64_or_higher', SymfonyVersionHelper::isSymfony64OrHigher());
        $container->setParameter('fhir.is_symfony_70_or_higher', SymfonyVersionHelper::isSymfony70OrHigher());
        $container->setParameter('fhir.is_symfony_74_or_higher', SymfonyVersionHelper::isSymfony74OrHigher());

        // IG generation configuration
        $container->setParameter('fhir.ig.packages', $config['ig']['packages']);
        $container->setParameter('fhir.ig.offline', $config['ig']['offline']);
        $container->setParameter('fhir.ig.output_directory', $config['ig']['output_directory']);
        $container->setParameter('fhir.ig.namespace', $config['ig']['namespace']);

        // Serialization metadata cache pool
        $cachePool         = $config['serialization']['metadata_cache_pool'] ?? null;
        $enableCacheWarmer = $config['serialization']['enable_cache_warmer'] ?? false;
        $container->setParameter('fhir.serialization.metadata_cache_pool', $cachePool);

        if ($cachePool !== null) {
            // Alias nominated PSR-6 pool so the warmer can depend on an abstract ID
            $container->setAlias('fhir.metadata_cache', $cachePool)->setPublic(false);

            // Register the cache warmer only when explicitly enabled
            if ($enableCacheWarmer) {
                $container->register(FHIRMetadataCacheWarmer::class, FHIRMetadataCacheWarmer::class)
                    ->setAutowired(false)
                    ->setArguments([
                        new Reference(PropertyMetadataProvider::class),
                        new Reference('fhir.metadata_cache'),
                    ])
                    ->addTag('kernel.cache_warmer')
                    ->setPublic(false);
            }
        }

        // Load service definitions
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        // Inject PSR-6 pool into PropertyMetadataProvider (services.yaml must be loaded first)
        if ($cachePool !== null) {
            $container->getDefinition(PropertyMetadataProvider::class)
                ->setArgument('$psrCache', new Reference('fhir.metadata_cache'));
        }
    }

    public function getAlias(): string
    {
        return 'fhir';
    }
}
