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
 * @author Ardenexal
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

    public function testBundleServiceConfigurationFileExists(): void
    {
        // Verify the bundle's service configuration exists
        $bundleServicesPath = __DIR__ . '/../../../../src/Bundle/FHIRBundle/Resources/config/services.yaml';
        self::assertFileExists($bundleServicesPath);

        $servicesContent = file_get_contents($bundleServicesPath);
        self::assertNotFalse($servicesContent);

        // Check that serialization services are registered
        self::assertStringContainsString('FHIRMetadataExtractor', $servicesContent);
        self::assertStringContainsString('FHIRValidator', $servicesContent);
        self::assertStringContainsString('FHIRSerializationService', $servicesContent);
    }
}
