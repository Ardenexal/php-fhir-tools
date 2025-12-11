<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Serialization;

use Ardenexal\FHIRTools\Serialization\FHIRMetadataExtractorInterface;
use Ardenexal\FHIRTools\Serialization\FHIRNormalizerInterface;
use Ardenexal\FHIRTools\Serialization\FHIRTypeResolverInterface;
use Ardenexal\FHIRTools\Tests\Utilities\FHIRTestDataGenerator;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;
use Eris\TestTrait;

/**
 * Test cases for FHIR serialization interfaces
 *
 * This test class verifies the core FHIR serialization interfaces using
 * property-based testing to ensure they support version-specific serialization
 * and maintain proper interface contracts.
 *
 * **Feature: fhir-serialization, Property 26: Version-specific serialization support**
 *
 * @author Kiro AI Assistant
 *
 * @since 1.0.0
 */
class FHIRSerializationInterfaceTest extends TestCase
{
    use TestTrait;

    /**
     * Property-based test for version-specific serialization support
     *
     * **Feature: fhir-serialization, Property 26: Version-specific serialization support**
     * **Validates: Requirements 6.4**
     */
    public function testVersionSpecificSerializationSupport(): void
    {
        $this->forAll(
            FHIRTestDataGenerator::fhirVersion(),
            FHIRTestDataGenerator::fhirResourceType(),
        )->then(function(string $fhirVersion, string $resourceType): void {
            // Create mock implementations that support version-specific behavior
            $normalizer        = $this->createMockNormalizer($fhirVersion);
            $typeResolver      = $this->createMockTypeResolver($fhirVersion);
            $metadataExtractor = $this->createMockMetadataExtractor($fhirVersion);

            // Test that interfaces support version-specific implementations
            self::assertInstanceOf(FHIRNormalizerInterface::class, $normalizer);
            self::assertInstanceOf(FHIRTypeResolverInterface::class, $typeResolver);
            self::assertInstanceOf(FHIRMetadataExtractorInterface::class, $metadataExtractor);

            // Test that version-specific behavior can be implemented
            $mockData = $this->createMockFHIRData($resourceType, $fhirVersion);

            // Verify normalizer supports version-specific normalization
            $supportsNormalization = $normalizer->supportsNormalization($mockData, 'json', ['fhir_version' => $fhirVersion]);
            self::assertTrue($supportsNormalization, "Normalizer should support version-specific normalization for FHIR {$fhirVersion}");

            // Verify type resolver can handle version-specific type resolution
            $resolvedType = $typeResolver->resolveType(['resourceType' => $resourceType], ['fhir_version' => $fhirVersion]);
            self::assertNotNull($resolvedType, "Type resolver should resolve types for FHIR {$fhirVersion}");

            // Verify metadata extractor can extract version information
            $extractedVersion = $metadataExtractor->extractFHIRVersion($mockData);
            self::assertEquals($fhirVersion, $extractedVersion, 'Metadata extractor should extract correct FHIR version');
        });
    }

    /**
     * Test FHIRNormalizerInterface contract compliance
     */
    public function testFHIRNormalizerInterfaceContract(): void
    {
        $this->forAll(
            FHIRTestDataGenerator::fhirVersion(),
        )->then(function(string $fhirVersion): void {
            $normalizer = $this->createMockNormalizer($fhirVersion);

            // Test required methods exist and return expected types
            self::assertTrue(method_exists($normalizer, 'supportsNormalization'));
            self::assertTrue(method_exists($normalizer, 'supportsDenormalization'));
            self::assertTrue(method_exists($normalizer, 'getSupportedTypes'));
            self::assertTrue(method_exists($normalizer, 'normalize'));
            self::assertTrue(method_exists($normalizer, 'denormalize'));

            // Test getSupportedTypes returns array
            $supportedTypes = $normalizer->getSupportedTypes('json');
            self::assertIsArray($supportedTypes, 'getSupportedTypes should return an array');
        });
    }

    /**
     * Test FHIRTypeResolverInterface contract compliance
     */
    public function testFHIRTypeResolverInterfaceContract(): void
    {
        $this->forAll(
            FHIRTestDataGenerator::fhirVersion(),
        )->then(function(string $fhirVersion): void {
            $typeResolver = $this->createMockTypeResolver($fhirVersion);

            // Test required methods exist
            self::assertTrue(method_exists($typeResolver, 'resolveType'));
            self::assertTrue(method_exists($typeResolver, 'getDiscriminatorProperty'));
            self::assertTrue(method_exists($typeResolver, 'getTypeMapping'));
            self::assertTrue(method_exists($typeResolver, 'resolveResourceType'));
            self::assertTrue(method_exists($typeResolver, 'resolveChoiceElementType'));
            self::assertTrue(method_exists($typeResolver, 'resolveReferenceType'));

            // Test getDiscriminatorProperty returns string
            $discriminatorProperty = $typeResolver->getDiscriminatorProperty();
            self::assertIsString($discriminatorProperty, 'getDiscriminatorProperty should return a string');

            // Test getTypeMapping returns array
            $typeMapping = $typeResolver->getTypeMapping();
            self::assertIsArray($typeMapping, 'getTypeMapping should return an array');
        });
    }

    /**
     * Test FHIRMetadataExtractorInterface contract compliance
     */
    public function testFHIRMetadataExtractorInterfaceContract(): void
    {
        $this->forAll(
            FHIRTestDataGenerator::fhirVersion(),
            FHIRTestDataGenerator::fhirResourceType(),
        )->then(function(string $fhirVersion, string $resourceType): void {
            $metadataExtractor = $this->createMockMetadataExtractor($fhirVersion);
            $mockObject        = $this->createMockFHIRData($resourceType, $fhirVersion);

            // Test required methods exist
            self::assertTrue(method_exists($metadataExtractor, 'extractResourceType'));
            self::assertTrue(method_exists($metadataExtractor, 'extractFHIRType'));
            self::assertTrue(method_exists($metadataExtractor, 'isResource'));
            self::assertTrue(method_exists($metadataExtractor, 'isComplexType'));
            self::assertTrue(method_exists($metadataExtractor, 'isPrimitiveType'));
            self::assertTrue(method_exists($metadataExtractor, 'isBackboneElement'));
            self::assertTrue(method_exists($metadataExtractor, 'extractFHIRVersion'));
            self::assertTrue(method_exists($metadataExtractor, 'extractParentResource'));
            self::assertTrue(method_exists($metadataExtractor, 'extractElementPath'));

            // Test methods return expected types
            $resourceType = $metadataExtractor->extractResourceType($mockObject);
            self::assertTrue($resourceType === null || is_string($resourceType));

            $fhirType = $metadataExtractor->extractFHIRType($mockObject);
            self::assertTrue($fhirType === null || is_string($fhirType));

            $isResource = $metadataExtractor->isResource($mockObject);
            self::assertIsBool($isResource);

            $extractedVersion = $metadataExtractor->extractFHIRVersion($mockObject);
            self::assertTrue($extractedVersion === null || is_string($extractedVersion));
        });
    }

    /**
     * Create a mock normalizer for testing
     */
    private function createMockNormalizer(string $fhirVersion): FHIRNormalizerInterface
    {
        return new class ($fhirVersion) implements FHIRNormalizerInterface {
            public function __construct(private readonly string $fhirVersion)
            {
            }

            public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
            {
                return isset($context['fhir_version']) && $context['fhir_version'] === $this->fhirVersion;
            }

            public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
            {
                return isset($context['fhir_version']) && $context['fhir_version'] === $this->fhirVersion;
            }

            public function getSupportedTypes(?string $format): array
            {
                return ['FHIRResource' => true, 'FHIRComplexType' => true];
            }

            public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
            {
                return ['resourceType' => 'TestResource', 'fhirVersion' => $this->fhirVersion];
            }

            public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
            {
                return (object) ['fhirVersion' => $this->fhirVersion];
            }
        };
    }

    /**
     * Create a mock type resolver for testing
     */
    private function createMockTypeResolver(string $fhirVersion): FHIRTypeResolverInterface
    {
        return new class ($fhirVersion) implements FHIRTypeResolverInterface {
            public function __construct(private readonly string $fhirVersion)
            {
            }

            public function resolveType(array $data, array $context = []): ?string
            {
                if (isset($context['fhir_version']) && $context['fhir_version'] === $this->fhirVersion) {
                    return $data['resourceType'] ?? 'UnknownType';
                }

                return null;
            }

            public function getDiscriminatorProperty(): string
            {
                return 'resourceType';
            }

            public function getTypeMapping(): array
            {
                return ['Patient' => 'FHIRPatient', 'Observation' => 'FHIRObservation'];
            }

            public function resolveResourceType(array $data): ?string
            {
                return $data['resourceType'] ?? null;
            }

            public function resolveChoiceElementType(string $propertyName, array $data): ?string
            {
                foreach ($data as $key => $value) {
                    if (str_starts_with($key, $propertyName)) {
                        return substr($key, strlen($propertyName));
                    }
                }

                return null;
            }

            public function resolveReferenceType(array $referenceData): ?string
            {
                return $referenceData['type'] ?? null;
            }
        };
    }

    /**
     * Create a mock metadata extractor for testing
     */
    private function createMockMetadataExtractor(string $fhirVersion): FHIRMetadataExtractorInterface
    {
        return new class ($fhirVersion) implements FHIRMetadataExtractorInterface {
            public function __construct(private readonly string $fhirVersion)
            {
            }

            public function extractResourceType(object $object): ?string
            {
                return $object->resourceType ?? null;
            }

            public function extractFHIRType(object $object): ?string
            {
                return $object->fhirType ?? null;
            }

            public function isResource(object $object): bool
            {
                return isset($object->resourceType);
            }

            public function isComplexType(object $object): bool
            {
                return isset($object->fhirType) && !isset($object->resourceType);
            }

            public function isPrimitiveType(object $object): bool
            {
                return isset($object->primitiveType);
            }

            public function isBackboneElement(object $object): bool
            {
                return isset($object->parentResource);
            }

            public function extractFHIRVersion(object $object): ?string
            {
                return $object->fhirVersion ?? $this->fhirVersion;
            }

            public function extractParentResource(object $object): ?string
            {
                return $object->parentResource ?? null;
            }

            public function extractElementPath(object $object): ?string
            {
                return $object->elementPath ?? null;
            }
        };
    }

    /**
     * Create mock FHIR data for testing
     */
    private function createMockFHIRData(string $resourceType, string $fhirVersion): object
    {
        return (object) [
            'resourceType' => $resourceType,
            'fhirVersion'  => $fhirVersion,
            'fhirType'     => $resourceType,
        ];
    }
}
