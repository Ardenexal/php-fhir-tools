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
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Json\FHIRBackboneElementJsonNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Json\FHIRComplexTypeJsonNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Json\FHIRPrimitiveTypeJsonNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Json\FHIRResourceJsonNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Xml\FHIRBackboneElementXmlNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Xml\FHIRComplexTypeXmlNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Xml\FHIRPrimitiveTypeXmlNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Xml\FHIRResourceXmlNormalizer;
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
 *   - A Symfony Serializer instance composed of those four normalizers for ear JSON and
 *     XML encoder.
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

        $resourceJsonId = "fhir.normalizer.resource.json.{$v}";
        $container->register($resourceJsonId, FHIRResourceJsonNormalizer::class)
            ->setArguments([
                new Reference(FHIRMetadataExtractorInterface::class),
                new Reference(FHIRTypeResolverInterface::class),
                null,             // normalizer (wired by Serializer via SerializerAwareInterface)
                null,             // denormalizer
                $version->value,  // fhirVersion
                $igRegistryRef,   // igTypeRegistry
            ])
            ->setPublic(false);

        $resourceXmlId = "fhir.normalizer.resource.xml.{$v}";
        $container->register($resourceXmlId, FHIRResourceXmlNormalizer::class)
            ->setArguments([
                new Reference(FHIRMetadataExtractorInterface::class),
                new Reference(FHIRTypeResolverInterface::class),
                null,
                null,
                $version->value,
                $igRegistryRef,
            ])
            ->setPublic(false);

        $complexJsonId = "fhir.normalizer.complex_type.json.{$v}";
        $container->register($complexJsonId, FHIRComplexTypeJsonNormalizer::class)
            ->setArguments([
                new Reference(FHIRMetadataExtractorInterface::class),
                new Reference(FHIRTypeResolverInterface::class),
                null,
                null,
                $version->value,
                $igRegistryRef,
            ])
            ->setPublic(false);

        $complexXmlId = "fhir.normalizer.complex_type.xml.{$v}";
        $container->register($complexXmlId, FHIRComplexTypeXmlNormalizer::class)
            ->setArguments([
                new Reference(FHIRMetadataExtractorInterface::class),
                new Reference(FHIRTypeResolverInterface::class),
                null,
                null,
                $version->value,
                $igRegistryRef,
            ])
            ->setPublic(false);

        $primitiveJsonId = "fhir.normalizer.primitive.json.{$v}";
        $container->register($primitiveJsonId, FHIRPrimitiveTypeJsonNormalizer::class)
            ->setArguments([
                new Reference(FHIRMetadataExtractorInterface::class),
                null,
                null,
                $version->value,
                $igRegistryRef,
            ])
            ->setPublic(false);

        $primitiveXmlId = "fhir.normalizer.primitive.xml.{$v}";
        $container->register($primitiveXmlId, FHIRPrimitiveTypeXmlNormalizer::class)
            ->setArguments([
                new Reference(FHIRMetadataExtractorInterface::class),
                null,
                null,
                $version->value,
                $igRegistryRef,
            ])
            ->setPublic(false);

        $backboneJsonId = "fhir.normalizer.backbone.json.{$v}";
        $container->register($backboneJsonId, FHIRBackboneElementJsonNormalizer::class)
            ->setArguments([
                new Reference(FHIRMetadataExtractorInterface::class),
                null,
                null,
                $version->value,
                $igRegistryRef,
            ])
            ->setPublic(false);

        $backboneXmlId = "fhir.normalizer.backbone.xml.{$v}";
        $container->register($backboneXmlId, FHIRBackboneElementXmlNormalizer::class)
            ->setArguments([
                new Reference(FHIRMetadataExtractorInterface::class),
                new Reference(FHIRTypeResolverInterface::class),
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
                    new Reference($resourceJsonId),
                    new Reference($resourceXmlId),
                    new Reference($complexJsonId),
                    new Reference($complexXmlId),
                    new Reference($primitiveJsonId),
                    new Reference($primitiveXmlId),
                    new Reference($backboneJsonId),
                    new Reference($backboneXmlId),
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
                new Reference(FHIRMetadataExtractorInterface::class),
            ])
            ->setPublic(true);
    }
}
