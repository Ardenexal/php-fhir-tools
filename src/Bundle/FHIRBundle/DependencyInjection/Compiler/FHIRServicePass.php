<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * FHIR Service Compiler Pass.
 *
 * Configures FHIR services with proper tagging and cross-references.
 * Handles automatic registration of FHIR generators and serializers.
 *
 * @author Ardenexal FHIRTools Team
 */
class FHIRServicePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $this->configureFHIRGenerators($container);
        $this->configureFHIRSerializers($container);
        $this->configureNormalizers($container);
    }

    private function configureFHIRGenerators(ContainerBuilder $container): void
    {
        $generators = $container->findTaggedServiceIds('fhir.generator');

        foreach ($generators as $id => $tags) {
            $definition = $container->getDefinition($id);

            // Mark generators as public for easier access
            $definition->setPublic(true);
        }
    }

    private function configureFHIRSerializers(ContainerBuilder $container): void
    {
        $serializers = $container->findTaggedServiceIds('fhir.serializer');

        foreach ($serializers as $id => $tags) {
            $definition = $container->getDefinition($id);

            // Mark serializers as public for easier access
            $definition->setPublic(true);
        }
    }

    private function configureNormalizers(ContainerBuilder $container): void
    {
        // Ensure all FHIR normalizers are properly registered with the serializer
        $normalizers = $container->findTaggedServiceIds('serializer.normalizer');

        foreach ($normalizers as $id => $tags) {
            $definition = $container->getDefinition($id);

            // Ensure FHIR normalizers are properly configured
            if (str_contains($id, 'FHIR')) {
                $definition->setAutowired(true);
            }
        }
    }
}
