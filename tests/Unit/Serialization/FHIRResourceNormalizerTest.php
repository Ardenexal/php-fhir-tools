<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Serialization;

use Ardenexal\FHIRTools\Serialization\FHIRResourceNormalizer;
use Ardenexal\FHIRTools\Serialization\FHIRMetadataExtractor;
use Ardenexal\FHIRTools\Serialization\FHIRTypeResolver;
use Ardenexal\FHIRTools\Tests\Fixtures\FHIR\FHIRPatient;
use Ardenexal\FHIRTools\Tests\Fixtures\FHIR\FHIRObservation;
use Ardenexal\FHIRTools\Tests\Fixtures\FHIR\FHIRString;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;
use Eris\Generator;
use Eris\TestTrait;

/**
 * Property-based tests for FHIRResourceNormalizer
 *
 * @author Kiro AI Assistant
 */
class FHIRResourceNormalizerTest extends TestCase
{
    use TestTrait;

    private FHIRResourceNormalizer $normalizer;

    private FHIRMetadataExtractor $metadataExtractor;

    private FHIRTypeResolver $typeResolver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->metadataExtractor = new FHIRMetadataExtractor();
        $this->typeResolver      = new FHIRTypeResolver([
            'Patient'     => FHIRPatient::class,
            'Observation' => FHIRObservation::class,
        ]);

        $this->normalizer = new FHIRResourceNormalizer(
            $this->metadataExtractor,
            $this->typeResolver,
        );
    }

    /**
     * **Feature: fhir-serialization, Property 1: FHIR JSON specification compliance**
     * **Validates: Requirements 1.1**
     *
     * For any FHIR resource, serializing to JSON should produce output that conforms
     * to the official FHIR JSON specification rules
     */
    public function testFHIRJSONSpecificationCompliance()
    {
        $this->forAll(
            $this->generateFHIRResource(),
        )->then(function($resource) {
            $normalized = $this->normalizer->normalize($resource, 'json');

            // Must be an array (JSON object)
            self::assertIsArray($normalized);

            // Must have resourceType as first field
            self::assertArrayHasKey('resourceType', $normalized);
            self::assertIsString($normalized['resourceType']);

            // resourceType should match the resource's type
            $expectedResourceType = $this->metadataExtractor->extractResourceType($resource);
            self::assertEquals($expectedResourceType, $normalized['resourceType']);

            // Should not contain null values (FHIR JSON rule)
            $this->assertNoNullValues($normalized);

            // Should not contain empty arrays (FHIR JSON rule)
            $this->assertNoEmptyArrays($normalized);

            // All keys should be valid FHIR element names (no underscores except for extensions)
            $this->assertValidFHIRElementNames($normalized);
        });
    }

    /**
     * Generate FHIR resource objects for testing
     */
    private function generateFHIRResource(): Generator
    {
        return Generator\oneOf(
            $this->generatePatientResource(),
            $this->generateObservationResource(),
        );
    }

    /**
     * Generate Patient resource
     */
    private function generatePatientResource(): Generator
    {
        return Generator\bind(
            Generator\choose(1, 999999),
            static fn ($id) => Generator\bind(
                Generator\elements(['male', 'female', 'other', 'unknown']),
                static fn ($gender) => Generator\bind(
                    Generator\elements(['1990-01-01', '1985-05-15', '2000-12-25', '1975-07-04']),
                    static fn ($birthDate) => Generator\constant(new FHIRPatient(
                        resourceType: 'Patient',
                        id: (string) $id,
                        gender: $gender,
                        birthDate: $birthDate,
                        name: [
                            [
                                'family' => 'TestFamily',
                                'given'  => ['TestGiven'],
                            ],
                        ],
                    )),
                ),
            ),
        );
    }

    /**
     * Generate Observation resource
     */
    private function generateObservationResource(): Generator
    {
        return Generator\bind(
            Generator\choose(1, 999999),
            static fn ($id) => Generator\bind(
                Generator\elements(['final', 'preliminary', 'amended']),
                static fn ($status) => Generator\bind(
                    Generator\oneOf(
                        Generator\string(),
                        Generator\int(),
                    ),
                    static fn ($value) => Generator\constant(new FHIRObservation(
                        resourceType: 'Observation',
                        id: (string) $id,
                        status: $status,
                        code: [
                            'coding' => [
                                [
                                    'system' => 'http://loinc.org',
                                    'code'   => '29463-7',
                                ],
                            ],
                        ],
                        valueString: is_string($value) ? $value : null,
                        valueInteger: is_int($value) ? $value : null,
                    )),
                ),
            ),
        );
    }

    /**
     * Assert that the normalized data contains no null values
     */
    private function assertNoNullValues(array $data): void
    {
        foreach ($data as $key => $value) {
            self::assertNotNull($value, "Found null value for key: {$key}");

            if (is_array($value)) {
                $this->assertNoNullValues($value);
            }
        }
    }

    /**
     * Assert that the normalized data contains no empty arrays
     */
    private function assertNoEmptyArrays(array $data): void
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                self::assertNotEmpty($value, "Found empty array for key: {$key}");
                $this->assertNoEmptyArrays($value);
            }
        }
    }

    /**
     * **Feature: fhir-serialization, Property 2: ResourceType inclusion**
     * **Validates: Requirements 1.2**
     *
     * For any FHIR resource with a resourceType field, the JSON output should include
     * the resourceType property
     */
    public function testResourceTypeInclusion()
    {
        $this->forAll(
            $this->generateFHIRResource(),
        )->then(function($resource) {
            $normalized = $this->normalizer->normalize($resource, 'json');

            // Must have resourceType field
            self::assertArrayHasKey('resourceType', $normalized);

            // resourceType must be a string
            self::assertIsString($normalized['resourceType']);

            // resourceType must not be empty
            self::assertNotEmpty($normalized['resourceType']);

            // resourceType should match the expected type from the resource
            $expectedResourceType = $this->metadataExtractor->extractResourceType($resource);
            self::assertEquals($expectedResourceType, $normalized['resourceType']);

            // resourceType should be the first field in the JSON (FHIR specification)
            $keys = array_keys($normalized);
            self::assertEquals('resourceType', $keys[0], 'resourceType should be the first field in FHIR JSON');
        });
    }

    /**
     * **Feature: fhir-serialization, Property 6: Correct class instantiation**
     * **Validates: Requirements 2.1**
     *
     * For any valid FHIR JSON input, deserialization should create instances
     * of the correct FHIR class types
     */
    public function testCorrectClassInstantiation()
    {
        $this->forAll(
            $this->generateValidFHIRJSON(),
        )->then(function($jsonData) {
            $resourceType  = $jsonData['resourceType'];
            $expectedClass = $this->getExpectedClassForResourceType($resourceType);

            if ($expectedClass === null) {
                // Skip if we don't have a mapping for this resource type
                return;
            }

            $denormalized = $this->normalizer->denormalize($jsonData, $expectedClass, 'json');

            // Should create an instance of the correct class
            self::assertInstanceOf($expectedClass, $denormalized);

            // Should be a FHIR resource
            self::assertTrue($this->metadataExtractor->isResource($denormalized));

            // Should have the correct resource type
            $actualResourceType = $this->metadataExtractor->extractResourceType($denormalized);
            self::assertEquals($resourceType, $actualResourceType);
        });
    }

    /**
     * Generate valid FHIR JSON data for testing deserialization
     */
    private function generateValidFHIRJSON(): Generator
    {
        return Generator\oneOf(
            $this->generatePatientJSON(),
            $this->generateObservationJSON(),
        );
    }

    /**
     * Generate Patient JSON data
     */
    private function generatePatientJSON(): Generator
    {
        return Generator\bind(
            Generator\choose(1, 999999),
            static fn ($id) => Generator\bind(
                Generator\elements(['male', 'female', 'other', 'unknown']),
                static fn ($gender) => Generator\constant([
                    'resourceType' => 'Patient',
                    'id'           => (string) $id,
                    'gender'       => $gender,
                    'name'         => [
                        [
                            'family' => 'TestFamily',
                            'given'  => ['TestGiven'],
                        ],
                    ],
                ]),
            ),
        );
    }

    /**
     * Generate Observation JSON data
     */
    private function generateObservationJSON(): Generator
    {
        return Generator\bind(
            Generator\choose(1, 999999),
            static fn ($id) => Generator\bind(
                Generator\elements(['final', 'preliminary', 'amended']),
                static fn ($status) => Generator\constant([
                    'resourceType' => 'Observation',
                    'id'           => (string) $id,
                    'status'       => $status,
                    'code'         => [
                        'coding' => [
                            [
                                'system' => 'http://loinc.org',
                                'code'   => '29463-7',
                            ],
                        ],
                    ],
                ]),
            ),
        );
    }

    /**
     * **Feature: fhir-serialization, Property 7: ResourceType-based class resolution**
     * **Validates: Requirements 2.2**
     *
     * For any JSON with a resourceType field, the deserializer should instantiate
     * the corresponding resource class
     */
    public function testResourceTypeBasedClassResolution()
    {
        $this->forAll(
            Generator\elements(['Patient', 'Observation']),
            Generator\choose(1, 999999),
        )->then(function($resourceType, $id) {
            $jsonData = [
                'resourceType' => $resourceType,
                'id'           => (string) $id,
            ];

            $expectedClass = $this->getExpectedClassForResourceType($resourceType);
            if ($expectedClass === null) {
                return; // Skip if no mapping
            }

            // Test that the type resolver correctly resolves the resource type
            $resolvedType = $this->typeResolver->resolveResourceType($jsonData);
            self::assertEquals($expectedClass, $resolvedType);

            // Test that denormalization uses the resolved type
            $denormalized = $this->normalizer->denormalize($jsonData, $expectedClass, 'json');

            // Should create correct class instance
            self::assertInstanceOf($expectedClass, $denormalized);

            // Should have correct resourceType property
            self::assertEquals($resourceType, $denormalized->resourceType);

            // Should extract correct resource type via metadata extractor
            $extractedType = $this->metadataExtractor->extractResourceType($denormalized);
            self::assertEquals($resourceType, $extractedType);
        });
    }

    /**
     * **Feature: fhir-serialization, Property 11: Resource normalizer selection**
     * **Validates: Requirements 3.1**
     *
     * For any FHIR resource object, the serializer should automatically select
     * and use the dedicated resource normalizer
     */
    public function testResourceNormalizerSelection()
    {
        $this->forAll(
            $this->generateFHIRResource(),
        )->then(function($resource) {
            // Test that the normalizer supports normalization of FHIR resources
            self::assertTrue(
                $this->normalizer->supportsNormalization($resource, 'json'),
                'Resource normalizer should support normalization of FHIR resources',
            );

            // Test that the normalizer can successfully normalize the resource
            $normalized = $this->normalizer->normalize($resource, 'json');
            self::assertIsArray($normalized);
            self::assertArrayHasKey('resourceType', $normalized);

            // Test that the normalizer correctly identifies the resource type
            $resourceType = $this->metadataExtractor->extractResourceType($resource);
            self::assertNotNull($resourceType);
            self::assertEquals($resourceType, $normalized['resourceType']);
        });
    }

    /**
     * Test that the normalizer correctly rejects non-resource objects
     */
    public function testResourceNormalizerRejectsNonResources()
    {
        $this->forAll(
            Generator\oneOf(
                Generator\string(),
                Generator\int(),
                Generator\constant(new \stdClass()),
                Generator\constant(new FHIRString('test')),  // Primitive type, not resource
            ),
        )->then(function($nonResource) {
            // Should not support normalization of non-resource objects
            self::assertFalse(
                $this->normalizer->supportsNormalization($nonResource, 'json'),
                'Resource normalizer should not support normalization of non-resource objects',
            );
        });
    }

    /**
     * **Feature: fhir-serialization, Property 16: Discriminator map type resolution**
     * **Validates: Requirements 4.1**
     *
     * For any polymorphic FHIR element, the serializer should use discriminator maps
     * to correctly determine and serialize the specific type
     */
    public function testDiscriminatorMapTypeResolution()
    {
        $this->forAll(
            Generator\elements(['Patient', 'Observation']),
            Generator\choose(1, 999999),
        )->then(function($resourceType, $id) {
            $jsonData = [
                'resourceType' => $resourceType,
                'id'           => (string) $id,
            ];

            // Test discriminator property identification
            self::assertEquals('resourceType', $this->typeResolver->getDiscriminatorProperty());

            // Test type mapping retrieval
            $typeMapping = $this->typeResolver->getTypeMapping();
            self::assertIsArray($typeMapping);

            // Test general type resolution
            $resolvedType  = $this->typeResolver->resolveType($jsonData);
            $expectedClass = $this->getExpectedClassForResourceType($resourceType);
            self::assertEquals($expectedClass, $resolvedType);

            // Test resource-specific type resolution
            $resourceResolvedType = $this->typeResolver->resolveResourceType($jsonData);
            self::assertEquals($expectedClass, $resourceResolvedType);

            // Test that the normalizer uses the resolved type correctly
            if ($expectedClass !== null) {
                $denormalized = $this->normalizer->denormalize($jsonData, $expectedClass, 'json');
                self::assertInstanceOf($expectedClass, $denormalized);
                self::assertEquals($resourceType, $denormalized->resourceType);
            }
        });
    }

    /**
     * Test choice element type resolution (value[x] patterns)
     */
    public function testChoiceElementTypeResolution()
    {
        $this->forAll(
            Generator\elements(['String', 'Integer', 'Boolean']),
            Generator\oneOf(
                Generator\string(),
                Generator\int(),
                Generator\bool(),
            ),
        )->then(function($typeSuffix, $value) {
            $choiceData = [
                'value' . $typeSuffix => $value,
            ];

            // Test choice element type resolution
            $resolvedType = $this->typeResolver->resolveChoiceElementType('value', $choiceData);

            // Should resolve to FHIR{Type} format
            $expectedType = 'FHIR' . $typeSuffix;
            self::assertEquals($expectedType, $resolvedType);
        });
    }

    /**
     * Test reference type resolution
     */
    public function testReferenceTypeResolution()
    {
        $this->forAll(
            Generator\elements(['Patient', 'Observation', 'Practitioner']),
            Generator\choose(1, 999999),
        )->then(function($resourceType, $id) {
            // Test reference with explicit type
            $referenceWithType = [
                'reference' => "{$resourceType}/{$id}",
                'type'      => $resourceType,
            ];

            $resolvedType  = $this->typeResolver->resolveReferenceType($referenceWithType);
            $expectedClass = $this->getExpectedClassForResourceType($resourceType) ?? 'FHIR' . $resourceType;
            self::assertEquals($expectedClass, $resolvedType);

            // Test reference without explicit type (should extract from reference URL)
            $referenceWithoutType = [
                'reference' => "{$resourceType}/{$id}",
            ];

            $resolvedTypeFromUrl = $this->typeResolver->resolveReferenceType($referenceWithoutType);
            self::assertEquals($expectedClass, $resolvedTypeFromUrl);
        });
    }

    /**
     * Get the expected class name for a resource type
     */
    private function getExpectedClassForResourceType(string $resourceType): ?string
    {
        $mapping = [
            'Patient'     => FHIRPatient::class,
            'Observation' => FHIRObservation::class,
        ];

        return $mapping[$resourceType] ?? null;
    }

    /**
     * Assert that all element names are valid FHIR names
     */
    private function assertValidFHIRElementNames(array $data): void
    {
        foreach ($data as $key => $value) {
            // Skip numeric keys (array indices)
            if (is_int($key)) {
                if (is_array($value)) {
                    $this->assertValidFHIRElementNames($value);
                }
                continue;
            }

            // Extension properties can start with underscore
            if (str_starts_with($key, '_')) {
                // Extension property names should correspond to a non-extension property
                $baseProperty = substr($key, 1);
                self::assertArrayHasKey($baseProperty, $data, "Extension property _{$baseProperty} has no corresponding base property");
            } else {
                // Regular property names should be valid FHIR element names
                self::assertMatchesRegularExpression('/^[a-zA-Z][a-zA-Z0-9]*$/', $key, "Invalid FHIR element name: {$key}");
            }

            if (is_array($value)) {
                $this->assertValidFHIRElementNames($value);
            }
        }
    }
}
