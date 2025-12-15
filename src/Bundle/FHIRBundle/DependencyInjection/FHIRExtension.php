<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * FHIR Bundle Extension for dependency injection configuration
 *
 * @author Ardenexal
 */
class FHIRExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new FHIRBundleConfiguration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        // Set configuration parameters
        $container->setParameter('fhir.generation.output_directory', $config['generation']['output_directory']);
        $container->setParameter('fhir.generation.base_namespace', $config['generation']['base_namespace']);
        $container->setParameter('fhir.generation.generate_tests', $config['generation']['generate_tests']);
        $container->setParameter('fhir.serialization.strict_validation', $config['serialization']['strict_validation']);
        $container->setParameter('fhir.serialization.cache_metadata', $config['serialization']['cache_metadata']);
    }
}