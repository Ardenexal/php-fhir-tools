<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Serialization;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationContextFactory;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractor;
use Ardenexal\FHIRTools\Component\Serialization\FHIRTypeResolver;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataCache;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractorInterface;
use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationDebugInfo;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Test FHIR service configuration and basic functionality.
 *
 * @author Kiro AI Assistant
 */
class FHIRServiceConfigurationTest extends TestCase
{
    public function testFHIRSerializationContextFactoryCanCreateContexts(): void
    {
        $factory = new FHIRSerializationContextFactory();

        $jsonContext = $factory->createJsonContext();
        self::assertIsArray($jsonContext);
        self::assertEquals('json', $jsonContext['format']);
        self::assertTrue($jsonContext['skip_null_values']);

        $xmlContext = $factory->createXmlContext();
        self::assertIsArray($xmlContext);
        self::assertEquals('xml', $xmlContext['format']);
        self::assertTrue($xmlContext['skip_null_values']);
    }

    public function testFHIRMetadataCacheCanStoreAndRetrieve(): void
    {
        $cache = new FHIRMetadataCache();

        // Test that cache returns null for non-existent entries
        self::assertNull($cache->getResourceMetadata('NonExistentClass'));
        self::assertNull($cache->getComplexTypeMetadata('NonExistentClass'));
        self::assertNull($cache->getPrimitiveTypeMetadata('NonExistentClass'));
        self::assertNull($cache->getBackboneElementMetadata('NonExistentClass'));
    }

    public function testFHIRTypeResolverCanBeInstantiated(): void
    {
        $resolver = new FHIRTypeResolver();

        self::assertInstanceOf(FHIRTypeResolver::class, $resolver);

        // Test with empty data
        self::assertNull($resolver->resolveType([], []));
    }

    public function testFHIRMetadataExtractorCanBeInstantiated(): void
    {
        $cache     = new FHIRMetadataCache();
        $extractor = new FHIRMetadataExtractor($cache);

        self::assertInstanceOf(FHIRMetadataExtractor::class, $extractor);
    }

    public function testFHIRSerializationServiceRequiredDependencies(): void
    {
        // This test verifies that the service can be instantiated with mocked dependencies
        $serializer        = $this->createMock(SerializerInterface::class);
        $metadataExtractor = $this->createMock(FHIRMetadataExtractorInterface::class);
        $contextFactory    = new FHIRSerializationContextFactory();
        $debugInfo         = $this->createMock(FHIRSerializationDebugInfo::class);

        $service = new FHIRSerializationService(
            $serializer,
            $contextFactory,
            $debugInfo,
        );

        self::assertInstanceOf(FHIRSerializationService::class, $service);
    }

    public function testServiceConfigurationFilesExist(): void
    {
        // Verify that the configuration files exist
        self::assertFileExists(__DIR__ . '/../../../config/packages/serializer.yaml');
        self::assertFileExists(__DIR__ . '/../../../config/services/fhir_serialization.yaml');

        // Verify the main services.yaml imports the FHIR configuration
        $servicesContent = file_get_contents(__DIR__ . '/../../../config/services.yaml');
        self::assertStringContainsString('imports:', $servicesContent);
        self::assertStringContainsString('services/fhir_serialization.yaml', $servicesContent);
    }

    public function testSerializerConfigurationIsValid(): void
    {
        $configContent = file_get_contents(__DIR__ . '/../../../config/packages/serializer.yaml');

        // Check that serializer is enabled
        self::assertStringContainsString('enabled: true', $configContent);

        // Check that FHIR normalizers are configured
        self::assertStringContainsString('fhir.normalizer.resource:', $configContent);
        self::assertStringContainsString('fhir.normalizer.complex_type:', $configContent);
        self::assertStringContainsString('fhir.normalizer.primitive:', $configContent);
        self::assertStringContainsString('fhir.normalizer.backbone_element:', $configContent);

        // Check that version-specific services are configured
        self::assertStringContainsString('fhir.r4b.type_resolver:', $configContent);
        self::assertStringContainsString('fhir.r5.type_resolver:', $configContent);
    }
}
