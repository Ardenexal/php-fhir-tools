<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

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

        // Load service definitions
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
    }

    public function getAlias(): string
    {
        return 'fhir';
    }
}
