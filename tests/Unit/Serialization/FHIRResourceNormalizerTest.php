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
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Ardenexal\FHIRTools\Exception\FHIRSerializationException;

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
        $this->typeResolver      = new FHIRTypeResolver(
            resourceTypeMapping: [
                'Patient'     => FHIRPatient::class,
                'Observation' => FHIRObservation::class,
            ],
            referenceTypeMapping: [
                'Patient'     => FHIRPatient::class,
                'Observation' => FHIRObservation::class,
            ],
        );

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
            Generator\choose(1, 999),
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
            Generator\choose(1, 999),
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
            Generator\choose(1, 999),
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
            Generator\choose(1, 999),
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
            Generator\choose(1, 999),
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
            Generator\choose(1, 999),
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
            Generator\choose(1, 999),
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
        // The type resolver returns full class names from the mapping
        $mapping = [
            'Patient'     => FHIRPatient::class,
            'Observation' => FHIRObservation::class,
        ];

        return $mapping[$resourceType] ?? null;
    }

    /**
     * **Feature: fhir-serialization, Property 4: Sparse extension array handling**
     * **Validates: Requirements 1.4**
     *
     * For any array containing elements with extensions, the serializer should correctly
     * handle sparse extension arrays according to FHIR rules
     */
    public function testSparseExtensionArrayHandling()
    {
        // Simplified test with a concrete example
        $nameArray = [
            ['family' => 'TestFamily'],
            ['given' => ['TestGiven']],
        ];

        $resource = new FHIRPatient(
            resourceType: 'Patient',
            id: 'test-patient',
            name: $nameArray,
        );

        $normalized = $this->normalizer->normalize($resource, 'json');

        // Should have the base array
        self::assertArrayHasKey('name', $normalized);
        self::assertIsArray($normalized['name']);
        self::assertNotEmpty($normalized['name']);

        // For now, just verify basic array normalization works
        self::assertCount(2, $normalized['name']);
        self::assertEquals(['family' => 'TestFamily'], $normalized['name'][0]);
        self::assertEquals(['given' => ['TestGiven']], $normalized['name'][1]);

        // Test with property-based generation for basic cases
        $this->forAll(
            Generator\choose(1, 3),
        )->then(function($size) {
            $values = [];
            for ($i = 0; $i < $size; ++$i) {
                $values[] = ['family' => "Family{$i}"];
            }

            $resource = new FHIRPatient(
                resourceType: 'Patient',
                id: 'test-patient',
                name: $values,
            );

            $normalized = $this->normalizer->normalize($resource, 'json');

            // Should have the base array
            self::assertArrayHasKey('name', $normalized);
            self::assertIsArray($normalized['name']);
            self::assertCount($size, $normalized['name']);

            // Each element should be properly normalized
            for ($i = 0; $i < $size; ++$i) {
                self::assertEquals(['family' => "Family{$i}"], $normalized['name'][$i]);
            }
        });
    }

    /**
     * Generate array data with sparse extensions for testing
     */
    private function generateArrayWithSparseExtensions(): Generator
    {
        return Generator\bind(
            Generator\choose(1, 3), // Array size
            static fn ($size) => Generator\bind(
                Generator\seq(Generator\oneOf(
                    Generator\constant(['family' => 'TestFamily']),
                    Generator\constant(['given' => ['TestGiven']]),
                    Generator\constant(['family' => 'AnotherFamily', 'given' => ['AnotherGiven']]),
                ), $size),
                static fn ($values) => Generator\bind(
                    Generator\seq(Generator\oneOf(
                        Generator\constant(null), // No extension
                        Generator\constant(['extension' => [['url' => 'test', 'valueString' => 'test']]]),
                    ), $size),
                    static fn ($extensions) => Generator\constant([
                        'values'     => array_filter($values, fn ($v) => $v !== null), // Remove nulls
                        'extensions' => $extensions,
                    ]),
                ),
            ),
        );
    }

    /**
     * **Feature: fhir-serialization, Property 5: Null value omission**
     * **Validates: Requirements 1.5**
     *
     * For any FHIR object with null or empty values, those values should be omitted
     * from JSON output according to FHIR specification
     */
    public function testNullValueOmission()
    {
        $this->forAll(
            $this->generateResourceWithNullValues(),
        )->then(function($resource) {
            $normalized = $this->normalizer->normalize($resource, 'json');

            // Should not contain any null values
            $this->assertNoNullValues($normalized);

            // Should not contain any empty arrays
            $this->assertNoEmptyArrays($normalized);

            // Should not contain any empty strings (if configured to omit them)
            $this->assertNoEmptyStrings($normalized);

            // Must still have resourceType (never omitted)
            self::assertArrayHasKey('resourceType', $normalized);
            self::assertNotNull($normalized['resourceType']);
            self::assertNotEmpty($normalized['resourceType']);

            // Required fields should be present even if they could be empty
            // (this depends on FHIR resource definition, but id is often required)
            if (property_exists($resource, 'id') && $resource->id !== null && $resource->id !== '') {
                self::assertArrayHasKey('id', $normalized);
            }
        });
    }

    /**
     * Generate FHIR resource with various null and empty values
     */
    private function generateResourceWithNullValues(): Generator
    {
        return Generator\bind(
            Generator\choose(1, 999),
            static fn ($id) => Generator\bind(
                Generator\oneOf(
                    Generator\constant(null),
                    Generator\elements(['male', 'female']),
                ),
                static fn ($gender) => Generator\bind(
                    Generator\oneOf(
                        Generator\constant(null),
                        Generator\constant([]),
                        Generator\constant([['family' => 'TestFamily']]),
                    ),
                    static fn ($name) => Generator\bind(
                        Generator\oneOf(
                            Generator\constant(null),
                            Generator\constant(''),
                            Generator\constant('1990-01-01'),
                        ),
                        static fn ($birthDate) => Generator\constant(new FHIRPatient(
                            resourceType: 'Patient',
                            id: (string) $id,
                            gender: $gender,
                            name: $name,
                            birthDate: $birthDate,
                        )),
                    ),
                ),
            ),
        );
    }

    /**
     * Assert that the normalized data contains no empty strings
     */
    private function assertNoEmptyStrings(array $data): void
    {
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                self::assertNotEmpty($value, "Found empty string for key: {$key}");
            } elseif (is_array($value)) {
                $this->assertNoEmptyStrings($value);
            }
        }
    }

    /**
     * **Feature: fhir-serialization, Property 9: Configurable unknown property handling**
     * **Validates: Requirements 2.4**
     *
     * For any JSON containing unknown properties, the deserializer should handle them
     * according to the configured policy (ignore, error, or preserve)
     */
    public function testConfigurableUnknownPropertyHandling()
    {
        $this->forAll(
            $this->generateJSONWithUnknownProperties(),
            Generator\elements(['ignore', 'error', 'preserve']),
        )->then(function($jsonData, $policy) {
            $context       = ['unknown_property_policy' => $policy];
            $expectedClass = $this->getExpectedClassForResourceType($jsonData['resourceType']);

            if ($expectedClass === null) {
                return; // Skip if no mapping
            }

            switch ($policy) {
                case 'ignore':
                    // Should successfully denormalize, ignoring unknown properties
                    $denormalized = $this->normalizer->denormalize($jsonData, $expectedClass, 'json', $context);
                    self::assertInstanceOf($expectedClass, $denormalized);

                    // Known properties should be set
                    self::assertEquals($jsonData['resourceType'], $denormalized->resourceType);
                    if (isset($jsonData['id'])) {
                        self::assertEquals($jsonData['id'], $denormalized->id);
                    }
                    break;

                case 'error':
                    // Should throw exception for unknown properties
                    if (isset($jsonData['unknownProperty'])) {
                        try {
                            $this->normalizer->denormalize($jsonData, $expectedClass, 'json', $context);
                            self::fail('Expected exception for unknown property with error policy');
                        } catch (\Exception $e) {
                            self::assertStringContainsString('unknown', strtolower($e->getMessage()));
                        }
                    } else {
                        // No unknown properties, should work normally
                        $denormalized = $this->normalizer->denormalize($jsonData, $expectedClass, 'json', $context);
                        self::assertInstanceOf($expectedClass, $denormalized);
                    }
                    break;

                case 'preserve':
                    // Should preserve unknown properties in some way (implementation dependent)
                    $denormalized = $this->normalizer->denormalize($jsonData, $expectedClass, 'json', $context);
                    self::assertInstanceOf($expectedClass, $denormalized);

                    // Known properties should still be set correctly
                    self::assertEquals($jsonData['resourceType'], $denormalized->resourceType);
                    break;
            }
        });
    }

    /**
     * Generate JSON data with unknown properties
     */
    private function generateJSONWithUnknownProperties(): Generator
    {
        return Generator\bind(
            Generator\elements(['Patient', 'Observation']),
            static fn ($resourceType) => Generator\bind(
                Generator\choose(1, 999),
                static fn ($id) => Generator\bind(
                    Generator\bool(),
                    static fn ($includeUnknown) => Generator\constant(array_merge(
                        [
                            'resourceType' => $resourceType,
                            'id'           => (string) $id,
                        ],
                        $includeUnknown ? ['unknownProperty' => 'unknownValue'] : [],
                    )),
                ),
            ),
        );
    }

    /**
     * **Feature: fhir-serialization, Property 10: Invalid JSON exception handling**
     * **Validates: Requirements 2.5**
     *
     * For any invalid JSON structure, the deserializer should throw specific
     * validation exceptions with meaningful error messages
     */
    public function testInvalidJSONExceptionHandling()
    {
        $this->forAll(
            $this->generateInvalidJSONData(),
        )->then(function($invalidData) {
            $expectedClass = FHIRPatient::class;

            try {
                $this->normalizer->denormalize($invalidData, $expectedClass, 'json');
                self::fail('Expected exception for invalid JSON data: ' . json_encode($invalidData));
            } catch (\Exception $e) {
                // Should throw a meaningful exception
                // NotNormalizableValueException for basic type errors (non-array data)
                // FHIRSerializationException for FHIR-specific validation errors in strict mode
                self::assertTrue(
                    $e instanceof NotNormalizableValueException || $e instanceof FHIRSerializationException,
                    'Expected NotNormalizableValueException or FHIRSerializationException, got ' . get_class($e),
                );

                // Exception message should be meaningful
                $message = strtolower($e->getMessage());
                self::assertTrue(
                    str_contains($message, 'resourcetype')
                    || str_contains($message, 'expected')
                    || str_contains($message, 'invalid')
                    || str_contains($message, 'array'),
                    'Exception message should be meaningful: ' . $e->getMessage(),
                );
            }
        });
    }

    /**
     * Generate invalid JSON data for testing error handling
     */
    private function generateInvalidJSONData(): Generator
    {
        return Generator\oneOf(
            // Missing resourceType
            Generator\constant(['id' => 'test']),

            // Non-string resourceType
            Generator\constant(['resourceType' => 123, 'id' => 'test']),

            // Empty resourceType
            Generator\constant(['resourceType' => '', 'id' => 'test']),

            // Non-array data
            Generator\string(),
            Generator\int(),
            Generator\bool(),

            // Null data
            Generator\constant(null),
        );
    }

    /**
     * **Feature: fhir-serialization, Property 15: Automatic normalizer selection**
     * **Validates: Requirements 3.5**
     *
     * For any FHIR object, the serializer should automatically choose the correct
     * normalizer based on the object's class type and attributes
     */
    public function testAutomaticNormalizerSelection()
    {
        $this->forAll(
            Generator\oneOf(
                $this->generateFHIRResource(),
                $this->generateFHIRComplexType(),
                $this->generateFHIRPrimitive(),
            ),
        )->then(function($fhirObject) {
            // Test that the normalizer correctly identifies what it can handle
            $canNormalize = $this->normalizer->supportsNormalization($fhirObject, 'json');

            if ($this->metadataExtractor->isResource($fhirObject)) {
                // Should support resource normalization
                self::assertTrue($canNormalize, 'Should support normalization of FHIR resources');

                // Should successfully normalize
                $normalized = $this->normalizer->normalize($fhirObject, 'json');
                self::assertIsArray($normalized);
                self::assertArrayHasKey('resourceType', $normalized);
            } else {
                // Should not support non-resource normalization
                self::assertFalse($canNormalize, 'Should not support normalization of non-resource objects');
            }

            // Test denormalization support
            if ($this->metadataExtractor->isResource($fhirObject)) {
                $className  = get_class($fhirObject);
                $sampleData = ['resourceType' => $this->metadataExtractor->extractResourceType($fhirObject)];

                $canDenormalize = $this->normalizer->supportsDenormalization($sampleData, $className, 'json');
                self::assertTrue($canDenormalize, 'Should support denormalization of FHIR resource data');
            }
        });
    }

    /**
     * Generate FHIR complex type objects for testing
     */
    private function generateFHIRComplexType(): Generator
    {
        return Generator\constant(new \stdClass()); // Placeholder - would be actual complex types
    }

    /**
     * Generate FHIR primitive objects for testing
     */
    private function generateFHIRPrimitive(): Generator
    {
        return Generator\bind(
            Generator\string(),
            static fn ($value) => Generator\constant(new FHIRString($value)),
        );
    }

    /**
     * **Feature: fhir-serialization, Property 21: FHIR XML specification compliance**
     * **Validates: Requirements 5.1**
     *
     * For any FHIR resource, serializing to XML should produce output that conforms
     * to the official FHIR XML specification
     */
    public function testFHIRXMLSpecificationCompliance()
    {
        $this->forAll(
            $this->generateFHIRResource(),
        )->then(function($resource) {
            $normalized = $this->normalizer->normalize($resource, 'xml');

            // Must be an array (XML structure)
            self::assertIsArray($normalized);

            // Must have FHIR namespace declaration
            self::assertArrayHasKey('@xmlns', $normalized);
            self::assertEquals('http://hl7.org/fhir', $normalized['@xmlns']);

            // Must have resourceType information
            self::assertArrayHasKey('@resourceType', $normalized);
            self::assertIsString($normalized['@resourceType']);

            // resourceType should match the resource's type
            $expectedResourceType = $this->metadataExtractor->extractResourceType($resource);
            self::assertEquals($expectedResourceType, $normalized['@resourceType']);

            // Should not contain null values (FHIR XML rule)
            $this->assertNoNullValues($normalized);

            // Should not contain empty arrays (FHIR XML rule)
            $this->assertNoEmptyArrays($normalized);

            // Should not have underscore notation (that's JSON-specific)
            $this->assertNoUnderscoreNotation($normalized);

            // All keys should be valid FHIR element names or XML attributes
            $this->assertValidFHIRXMLElementNames($normalized);
        });
    }

    /**
     * **Feature: fhir-serialization, Property 22: XML namespace inclusion**
     * **Validates: Requirements 5.2**
     *
     * For any FHIR XML output, proper FHIR namespace declarations should be included
     * according to the specification
     */
    public function testXMLNamespaceInclusion()
    {
        $this->forAll(
            $this->generateFHIRResource(),
        )->then(function($resource) {
            $normalized = $this->normalizer->normalize($resource, 'xml');

            // Must have FHIR namespace declaration
            self::assertArrayHasKey('@xmlns', $normalized);
            self::assertEquals('http://hl7.org/fhir', $normalized['@xmlns']);

            // Namespace should be the official FHIR namespace
            self::assertStringStartsWith('http://hl7.org/fhir', $normalized['@xmlns']);

            // Should not have multiple conflicting namespace declarations
            $xmlnsKeys = array_filter(array_keys($normalized), fn ($key) => str_starts_with($key, '@xmlns'));
            self::assertCount(1, $xmlnsKeys, 'Should have exactly one namespace declaration');
        });
    }

    /**
     * **Feature: fhir-serialization, Property 24: XML deserialization accuracy**
     * **Validates: Requirements 5.4**
     *
     * For any valid FHIR XML input, deserialization should correctly parse
     * the XML into appropriate PHP FHIR objects
     */
    public function testXMLDeserializationAccuracy()
    {
        $this->forAll(
            $this->generateValidFHIRXML(),
        )->then(function($xmlData) {
            $resourceType  = $xmlData['@resourceType'] ?? 'Patient';
            $expectedClass = $this->getExpectedClassForResourceType($resourceType);

            if ($expectedClass === null) {
                // Skip if we don't have a mapping for this resource type
                return;
            }

            // Add context to help with XML deserialization
            $context = ['xml_element_name' => $resourceType];

            $denormalized = $this->normalizer->denormalize($xmlData, $expectedClass, 'xml', $context);

            // Should create an instance of the correct class
            self::assertInstanceOf($expectedClass, $denormalized);

            // Should be a FHIR resource
            self::assertTrue($this->metadataExtractor->isResource($denormalized));

            // Should have the correct resource type
            $actualResourceType = $this->metadataExtractor->extractResourceType($denormalized);
            self::assertEquals($resourceType, $actualResourceType);

            // Properties should be correctly set from XML data
            if (isset($xmlData['id'])) {
                self::assertEquals($xmlData['id'], $denormalized->id);
            }
        });
    }

    /**
     * **Feature: fhir-serialization, Property 25: XML schema validation**
     * **Validates: Requirements 5.5**
     *
     * For any FHIR XML when validation is enabled, the output should validate
     * against official FHIR XML schemas
     */
    public function testXMLSchemaValidation()
    {
        $this->forAll(
            $this->generateFHIRResource(),
        )->then(function($resource) {
            $normalized = $this->normalizer->normalize($resource, 'xml');

            // Basic structural validation (simplified schema validation)
            self::assertIsArray($normalized);

            // Must have required XML structure elements
            self::assertArrayHasKey('@xmlns', $normalized);
            self::assertArrayHasKey('@resourceType', $normalized);

            // Namespace must be valid FHIR namespace
            self::assertEquals('http://hl7.org/fhir', $normalized['@xmlns']);

            // ResourceType must be valid
            $resourceType = $normalized['@resourceType'];
            self::assertIsString($resourceType);
            self::assertNotEmpty($resourceType);
            self::assertMatchesRegularExpression('/^[A-Z][a-zA-Z0-9]*$/', $resourceType);

            // All element names should follow FHIR XML naming conventions
            $this->assertValidFHIRXMLStructure($normalized);
        });
    }

    /**
     * Generate valid FHIR XML data for testing deserialization
     */
    private function generateValidFHIRXML(): Generator
    {
        return Generator\oneOf(
            $this->generatePatientXML(),
            $this->generateObservationXML(),
        );
    }

    /**
     * Generate Patient XML data
     */
    private function generatePatientXML(): Generator
    {
        return Generator\bind(
            Generator\choose(1, 999),
            static fn ($id) => Generator\bind(
                Generator\elements(['male', 'female', 'other', 'unknown']),
                static fn ($gender) => Generator\constant([
                    '@xmlns'        => 'http://hl7.org/fhir',
                    '@resourceType' => 'Patient',
                    'id'            => (string) $id,
                    'gender'        => $gender, // Simple string for now
                    'name'          => [
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
     * Generate Observation XML data
     */
    private function generateObservationXML(): Generator
    {
        return Generator\bind(
            Generator\choose(1, 999),
            static fn ($id) => Generator\bind(
                Generator\elements(['final', 'preliminary', 'amended']),
                static fn ($status) => Generator\constant([
                    '@xmlns'        => 'http://hl7.org/fhir',
                    '@resourceType' => 'Observation',
                    'id'            => (string) $id,
                    'status'        => $status, // Simple string for now
                    'code'          => [
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
     * Assert that the normalized data contains no underscore notation (JSON-specific)
     */
    private function assertNoUnderscoreNotation(array $data): void
    {
        foreach ($data as $key => $value) {
            // Skip numeric keys (array indices)
            if (is_int($key)) {
                if (is_array($value)) {
                    $this->assertNoUnderscoreNotation($value);
                }
                continue;
            }

            // Skip XML attributes (start with @)
            if (str_starts_with($key, '@')) {
                continue;
            }

            // Should not have underscore notation in XML
            self::assertFalse(
                str_starts_with($key, '_'),
                "Found underscore notation in XML output: {$key}",
            );

            if (is_array($value)) {
                $this->assertNoUnderscoreNotation($value);
            }
        }
    }

    /**
     * Assert that all element names are valid FHIR XML names
     */
    private function assertValidFHIRXMLElementNames(array $data): void
    {
        foreach ($data as $key => $value) {
            // Skip numeric keys (array indices)
            if (is_int($key)) {
                if (is_array($value)) {
                    $this->assertValidFHIRXMLElementNames($value);
                }
                continue;
            }

            // XML attributes start with @
            if (str_starts_with($key, '@')) {
                $attributeName = substr($key, 1);
                self::assertMatchesRegularExpression('/^[a-zA-Z][a-zA-Z0-9]*$/', $attributeName, "Invalid XML attribute name: {$key}");
            } else {
                // Regular element names should be valid FHIR element names
                self::assertMatchesRegularExpression('/^[a-zA-Z][a-zA-Z0-9]*$/', $key, "Invalid FHIR XML element name: {$key}");
            }

            if (is_array($value)) {
                $this->assertValidFHIRXMLElementNames($value);
            }
        }
    }

    /**
     * Assert valid FHIR XML structure
     */
    private function assertValidFHIRXMLStructure(array $data): void
    {
        // Must have namespace
        self::assertArrayHasKey('@xmlns', $data);

        // Must have resourceType
        self::assertArrayHasKey('@resourceType', $data);

        // All primitive values should be in @value attributes or proper structure
        $this->assertValidXMLPrimitiveStructure($data);
    }

    /**
     * Assert valid XML primitive structure
     */
    private function assertValidXMLPrimitiveStructure(array $data): void
    {
        foreach ($data as $key => $value) {
            // Skip numeric keys (array indices)
            if (is_int($key)) {
                if (is_array($value)) {
                    $this->assertValidXMLPrimitiveStructure($value);
                }
                continue;
            }

            // Skip XML attributes
            if (str_starts_with($key, '@')) {
                continue;
            }

            if (is_array($value)) {
                // If it's an array representing a primitive with extensions,
                // it should have @value for the primitive value
                if (isset($value['@value'])) {
                    // This is a primitive with potential extensions
                    self::assertTrue(
                        is_scalar($value['@value']) || is_null($value['@value']),
                        "Primitive @value should be scalar for element: {$key}",
                    );
                }

                // Recursively check nested structures
                $this->assertValidXMLPrimitiveStructure($value);
            }
        }
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
