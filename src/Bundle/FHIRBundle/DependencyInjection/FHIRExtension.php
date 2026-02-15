<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\DependencyInjection;

use Ardenexal\FHIRTools\Bundle\FHIRBundle\Compatibility\SymfonyVersionHelper;
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

        // Load service definitions
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
    }

    public function getAlias(): string
    {
        return 'fhir';
    }
}
