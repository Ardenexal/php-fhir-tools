<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\DependencyInjection\Compiler;

use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationContextFactory;
use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationDebugInfo;
use Ardenexal\FHIRTools\Component\Serialization\FHIRIGTypeRegistry;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FHIRVersionedSerializationServiceLocator;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractorInterface;
use Ardenexal\FHIRTools\Component\Serialization\FHIRTypeResolverInterface;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\FHIRBackboneElementNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\FHIRComplexTypeNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\FHIRPrimitiveTypeNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\FHIRResourceNormalizer;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Serializer;

/**
 * Registers per-version FHIR serialization service stacks.
 *
 * For each supported FHIR version (R4, R4B, R5) this pass programmatically
 * creates and registers:
 *
 *   - Four normalizer service definitions (resource, complex-type, primitive, backbone)
 *     each wired to the correct FHIR version string so the base Extension FQCN is
 *     resolved at construction time without any runtime class_exists probing.
 *
 *   - A Symfony Serializer instance composed of those four normalizers plus JSON and
 *     XML encoders.
 *
 *   - A FHIRSerializationService instance (public, ID: fhir.serialization_service.{version})
 *     wrapping the version-scoped Serializer.
 *
 * After all three version stacks are registered this pass also registers:
 *
 *   - FHIRVersionedSerializationServiceLocator (public) — holds all three services for
 *     runtime version selection.
 *
 *   - Backward-compatible aliases:
 *       fhir.serialization_service              → fhir.serialization_service.{default_version}
 *       FHIRSerializationService FQCN           → fhir.serialization_service.{default_version}
 *
 * The default version is read from the `fhir.default_version` container parameter, which
 * is set by FHIRExtension::load() from the fhir.default_version bundle configuration key.
 *
 * @author Ardenexal
 */
class FHIRVersionedSerializerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        /** @var string $defaultVersion */
        $defaultVersion = $container->getParameter('fhir.default_version');
        $defaultId      = 'fhir.serialization_service.' . strtolower($defaultVersion);

        foreach (FhirVersion::cases() as $version) {
            $this->registerVersionStack($container, $version);
        }

        // Locator: holds all three versioned services for runtime version selection
        $container->register(FHIRVersionedSerializationServiceLocator::class, FHIRVersionedSerializationServiceLocator::class)
            ->setArguments([
                new Reference('fhir.serialization_service.r4'),
                new Reference('fhir.serialization_service.r4b'),
                new Reference('fhir.serialization_service.r5'),
            ])
            ->setPublic(true);

        // Backward-compat aliases — both point at the configured default version
        $container->setAlias(FHIRSerializationService::class, $defaultId)->setPublic(true);
        $container->setAlias('fhir.serialization_service', $defaultId)->setPublic(true);
    }

    /**
     * Register the normalizer stack, Serializer, and FHIRSerializationService for one version.
     */
    private function registerVersionStack(ContainerBuilder $container, FhirVersion $version): void
    {
        $v = strtolower($version->value);

        // ---- Normalizers (null normalizer/denormalizer avoids circular DI;
        //      Serializer calls setSerializer() on each via SerializerAwareInterface) ----

        $igRegistryRef = new Reference(FHIRIGTypeRegistry::class);

        $resourceId = "fhir.normalizer.resource.{$v}";
        $container->register($resourceId, FHIRResourceNormalizer::class)
            ->setArguments([
                new Reference(FHIRMetadataExtractorInterface::class),
                new Reference(FHIRTypeResolverInterface::class),
                null,             // normalizer (wired by Serializer via SerializerAwareInterface)
                null,             // denormalizer
                $version->value,  // fhirVersion
                $igRegistryRef,   // igTypeRegistry
            ])
            ->setPublic(false);

        $complexId = "fhir.normalizer.complex_type.{$v}";
        $container->register($complexId, FHIRComplexTypeNormalizer::class)
            ->setArguments([
                new Reference(FHIRMetadataExtractorInterface::class),
                new Reference(FHIRTypeResolverInterface::class),
                null,
                null,
                $version->value,
                $igRegistryRef,
            ])
            ->setPublic(false);

        $primitiveId = "fhir.normalizer.primitive.{$v}";
        $container->register($primitiveId, FHIRPrimitiveTypeNormalizer::class)
            ->setArguments([
                new Reference(FHIRMetadataExtractorInterface::class),
                null,
                null,
                $version->value,
                $igRegistryRef,
            ])
            ->setPublic(false);

        $backboneId = "fhir.normalizer.backbone.{$v}";
        $container->register($backboneId, FHIRBackboneElementNormalizer::class)
            ->setArguments([
                new Reference(FHIRMetadataExtractorInterface::class),
                null,
                null,
                $version->value,
                $igRegistryRef,
            ])
            ->setPublic(false);

        // ---- Version-scoped Symfony Serializer ----

        $serializerId = "fhir.serializer.{$v}";
        $container->register($serializerId, Serializer::class)
            ->setArguments([
                [
                    new Reference($resourceId),
                    new Reference($complexId),
                    new Reference($primitiveId),
                    new Reference($backboneId),
                ],
                [
                    new Definition(JsonEncoder::class),
                    new Definition(XmlEncoder::class),
                ],
            ])
            ->setPublic(false);

        // ---- FHIRSerializationService (public, named by version) ----

        $container->register("fhir.serialization_service.{$v}", FHIRSerializationService::class)
            ->setArguments([
                new Reference($serializerId),
                new Reference(FHIRSerializationContextFactory::class),
                new Reference(FHIRSerializationDebugInfo::class),
            ])
            ->setPublic(true);
    }
}
