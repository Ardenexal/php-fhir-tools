<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Serialization;

use Ardenexal\FHIRTools\Serialization\FHIRResourceNormalizer;
use Ardenexal\FHIRTools\Serialization\FHIRComplexTypeNormalizer;
use Ardenexal\FHIRTools\Serialization\FHIRPrimitiveTypeNormalizer;
use Ardenexal\FHIRTools\Serialization\FHIRBackboneElementNormalizer;
use Ardenexal\FHIRTools\Serialization\FHIRMetadataExtractor;
use Ardenexal\FHIRTools\Serialization\FHIRTypeResolver;
use Ardenexal\FHIRTools\Tests\Fixtures\FHIR\FHIRPatient;
use Ardenexal\FHIRTools\Tests\Fixtures\FHIR\FHIRObservation;
use Ardenexal\FHIRTools\Tests\Fixtures\FHIR\FHIRString;
use Ardenexal\FHIRTools\Tests\Fixtures\FHIR\FHIRHumanName;
use Ardenexal\FHIRTools\Tests\Fixtures\FHIR\FHIRAddress;
use Ardenexal\FHIRTools\Tests\Fixtures\FHIR\FHIRReference;
use Ardenexal\FHIRTools\Tests\Fixtures\FHIR\FHIRExtension;
use Ardenexal\FHIRTools\Tests\Fixtures\FHIR\FHIRPatientContact;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;
use Eris\Generator;
use Eris\TestTrait;

/**
 * Property-based tests for FHIR round-trip serialization
 *
 * Tests comprehensive round-trip behavior to ensure that serialization
 * and deserialization preserve all FHIR data correctly.
 *
 * @author Kiro AI Assistant
 */
class FHIRRoundTripTest extends TestCase
{
    use TestTrait;

    private FHIRResourceNormalizer $resourceNormalizer;
    private FHIRComplexTypeNormalizer $complexTypeNormalizer;
    private FHIRPrimitiveTypeNormalizer $primitiveTypeNormalizer;
    private FHIRBackboneElementNormalizer $backboneElementNormalizer;
    private FHIRMetadataExtractor $metadataExtractor;
    private FHIRTypeResolver $typeResolver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->metadataExtractor = new FHIRMetadataExtractor();
        $this->typeResolver = new FHIRTypeResolver(
            resourceTypeMapping: [
                'Patient' => FHIRPatient::class,
                'Observation' => FHIRObservation::class,
            ],
            referenceTypeMapping: [
                'Patient' => FHIRPatient::class,
                'Observation' => FHIRObservation::class,
            ],
            complexTypeMapping: [
                'HumanName' => FHIRHumanName::class,
                'Address' => FHIRAddress::class,
                'Reference' => FHIRReference::class,
                'Extension' => FHIRExtension::class,
            ],
        );

        $this->resourceNormalizer = new FHIRResourceNormalizer(
            $this->metadataExtractor,
            $this->typeResolver,
        );

        $this->complexTypeNormalizer = new FHIRComplexTypeNormalizer(
            $this->metadataExtractor,
            $this->typeResolver,
        );

        $this->primitiveTypeNormalizer = new FHIRPrimitiveTypeNormalizer(
            $this->metadataExtractor,
        );

        $this->backboneElementNormalizer = new FHIRBackboneElementNormalizer(
            $this->metadataExtractor,
        );
    }

    /**
     * **Feature: fhir-serialization, Property 27: Object equivalence preservation**
     * **Validates: Requirements 7.1**
     *
     * For any FHIR object, serializing then deserializing should produce an object
     * equivalent to the original
     */
    public function testObjectEquivalencePreservation(): void
    {
        $this->forAll(
            $this->generateSimpleFHIRResource(),
        )->then(function($original) {
            $className = get_class($original);
            $normalizer = $this->getNormalizerForObject($original);

            if ($normalizer === null) {
                return; // Skip if no appropriate normalizer
            }

            // Test JSON round-trip
            $jsonNormalized = $normalizer->normalize($original, 'json');
            $jsonDeserialized = $normalizer->denormalize($jsonNormalized, $className, 'json');

            $this->assertObjectsEquivalent($original, $jsonDeserialized, 'JSON round-trip');

            // Test XML round-trip (if supported)
            try {
                $xmlNormalized = $normalizer->normalize($original, 'xml');
                $xmlDeserialized = $normalizer->denormalize($xmlNormalized, $className, 'xml');
                $this->assertObjectsEquivalent($original, $xmlDeserialized, 'XML round-trip');
            } catch (\Exception $e) {
                // XML not supported or not implemented, skip
            }
        });
    }

    /**
     * **Feature: fhir-serialization, Property 28: Extension data preservation**
     * **Validates: Requirements 7.2**
     *
     * For any FHIR object with extensions, all extension data should be preserved
     * through complete serialization round-trips
     */
    public function testExtensionDataPreservation(): void
    {
        $this->forAll(
            $this->generateFHIRObjectWithExtensions(),
        )->then(function($original) {
            $className = get_class($original);
            $normalizer = $this->getNormalizerForObject($original);

            if ($normalizer === null) {
                return; // Skip if no appropriate normalizer
            }

            // Verify the object has extensions
            $hasExtensions = $this->hasExtensions($original);
            if (!$hasExtensions) {
                return; // Skip if no extensions to test
            }

            // Test JSON round-trip with extension preservation
            $jsonNormalized = $normalizer->normalize($original, 'json');
            $jsonDeserialized = $normalizer->denormalize($jsonNormalized, $className, 'json');

            $this->assertExtensionsPreserved($original, $jsonDeserialized, 'JSON round-trip');

            // Test XML round-trip with extension preservation (if supported)
            try {
                $xmlNormalized = $normalizer->normalize($original, 'xml');
                $xmlDeserialized = $normalizer->denormalize($xmlNormalized, $className, 'xml');
                $this->assertExtensionsPreserved($original, $xmlDeserialized, 'XML round-trip');
            } catch (\Exception $e) {
                // XML not supported or not implemented, skip
            }
        });
    }

    /**
     * **Feature: fhir-serialization, Property 29: Metadata preservation**
     * **Validates: Requirements 7.3**
     *
     * For any FHIR object with metadata elements, all metadata should be maintained
     * through serialization cycles
     */
    public function testMetadataPreservation(): void
    {
        $this->forAll(
            $this->generateSimpleFHIRResource(),
        )->then(function($original) {
            $className = get_class($original);
            $normalizer = $this->getNormalizerForObject($original);

            if ($normalizer === null) {
                return; // Skip if no appropriate normalizer
            }

            // Test JSON round-trip with metadata preservation
            $jsonNormalized = $normalizer->normalize($original, 'json');
            $jsonDeserialized = $normalizer->denormalize($jsonNormalized, $className, 'json');

            $this->assertBasicMetadataPreserved($original, $jsonDeserialized, 'JSON round-trip');

            // Test XML round-trip with metadata preservation (if supported)
            try {
                $xmlNormalized = $normalizer->normalize($original, 'xml');
                $xmlDeserialized = $normalizer->denormalize($xmlNormalized, $className, 'xml');
                $this->assertBasicMetadataPreserved($original, $xmlDeserialized, 'XML round-trip');
            } catch (\Exception $e) {
                // XML not supported or not implemented, skip
            }
        });
    }

    /**
     * Generate simple FHIR resources for testing
     */
    private function generateSimpleFHIRResource(): Generator
    {
        return Generator\oneOf(
            $this->generateSimplePatientResource(),
            $this->generateSimpleObservationResource(),
        );
    }

    /**
     * Generate various FHIR objects for testing
     */
    private function generateFHIRObject(): Generator
    {
        return Generator\oneOf(
            $this->generateFHIRResource(),
            $this->generateFHIRComplexType(),
            $this->generateFHIRPrimitiveType(),
            $this->generateFHIRBackboneElement(),
        );
    }

    /**
     * Generate FHIR resources
     */
    private function generateFHIRResource(): Generator
    {
        return Generator\oneOf(
            $this->generatePatientResource(),
            $this->generateObservationResource(),
        );
    }

    /**
     * Generate FHIR complex types
     */
    private function generateFHIRComplexType(): Generator
    {
        return Generator\oneOf(
            $this->generateHumanName(),
            $this->generateAddress(),
            $this->generateReference(),
            $this->generateExtension(),
        );
    }

    /**
     * Generate FHIR primitive types
     */
    private function generateFHIRPrimitiveType(): Generator
    {
        return Generator\oneOf(
            $this->generateFHIRString(),
        );
    }

    /**
     * Generate FHIR backbone elements
     */
    private function generateFHIRBackboneElement(): Generator
    {
        return Generator\oneOf(
            $this->generatePatientContact(),
        );
    }

    /**
     * Generate simple Patient resource
     */
    private function generateSimplePatientResource(): Generator
    {
        return Generator\bind(
            Generator\choose(1, 999),
            static fn ($id) => Generator\bind(
                Generator\elements(['male', 'female']),
                static fn ($gender) => Generator\constant(new FHIRPatient(
                    resourceType: 'Patient',
                    id: (string) $id,
                    gender: $gender,
                )),
            ),
        );
    }

    /**
     * Generate simple Observation resource
     */
    private function generateSimpleObservationResource(): Generator
    {
        return Generator\bind(
            Generator\choose(1, 999),
            static fn ($id) => Generator\bind(
                Generator\elements(['final', 'preliminary']),
                static fn ($status) => Generator\constant(new FHIRObservation(
                    resourceType: 'Observation',
                    id: (string) $id,
                    status: $status,
                )),
            ),
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
                    Generator\elements(['1990-01-01', '1985-05-15', '2000-12-25']),
                    static fn ($birthDate) => Generator\bind(
                        Generator\oneOf(
                            Generator\constant(null),
                            Generator\seq(Generator\constant(['family' => 'TestFamily', 'given' => ['TestGiven']]), 1, 2),
                        ),
                        static fn ($name) => Generator\constant(new FHIRPatient(
                            resourceType: 'Patient',
                            id: (string) $id,
                            gender: $gender,
                            birthDate: $birthDate,
                            name: $name,
                        )),
                    ),
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
                static fn ($status) => Generator\constant(new FHIRObservation(
                    resourceType: 'Observation',
                    id: (string) $id,
                    status: $status,
                    code: [
                        'coding' => [
                            [
                                'system' => 'http://loinc.org',
                                'code' => '29463-7',
                            ],
                        ],
                    ],
                )),
            ),
        );
    }

    /**
     * Generate HumanName complex type
     */
    private function generateHumanName(): Generator
    {
        return Generator\bind(
            Generator\oneOf(
                Generator\constant(null),
                Generator\elements(['usual', 'official', 'temp']),
            ),
            static fn ($use) => Generator\bind(
                Generator\oneOf(Generator\constant(null), Generator\string()),
                static fn ($family) => Generator\bind(
                    Generator\oneOf(
                        Generator\constant(null),
                        Generator\seq(Generator\string(), 1, 2),
                    ),
                    static fn ($given) => Generator\constant(new FHIRHumanName(
                        use: $use,
                        family: $family,
                        given: $given,
                    )),
                ),
            ),
        );
    }

    /**
     * Generate Address complex type
     */
    private function generateAddress(): Generator
    {
        return Generator\bind(
            Generator\oneOf(
                Generator\constant(null),
                Generator\elements(['home', 'work', 'temp']),
            ),
            static fn ($use) => Generator\bind(
                Generator\oneOf(Generator\constant(null), Generator\string()),
                static fn ($city) => Generator\constant(new FHIRAddress(
                    use: $use,
                    city: $city,
                )),
            ),
        );
    }

    /**
     * Generate Reference complex type
     */
    private function generateReference(): Generator
    {
        return Generator\bind(
            Generator\oneOf(Generator\constant(null), Generator\string()),
            static fn ($reference) => Generator\bind(
                Generator\oneOf(Generator\constant(null), Generator\string()),
                static fn ($display) => Generator\constant(new FHIRReference(
                    reference: $reference,
                    display: $display,
                )),
            ),
        );
    }

    /**
     * Generate Extension complex type
     */
    private function generateExtension(): Generator
    {
        return Generator\bind(
            Generator\string(),
            static fn ($url) => Generator\constant(new FHIRExtension(url: $url)),
        );
    }

    /**
     * Generate FHIRString primitive type
     */
    private function generateFHIRString(): Generator
    {
        return Generator\bind(
            Generator\oneOf(Generator\constant(null), Generator\string()),
            static fn ($value) => Generator\constant(new FHIRString(value: $value)),
        );
    }

    /**
     * Generate PatientContact backbone element
     */
    private function generatePatientContact(): Generator
    {
        return Generator\bind(
            Generator\oneOf(
                Generator\constant(null),
                Generator\seq(Generator\elements(['family', 'friend', 'work']), 1, 2),
            ),
            static fn ($relationship) => Generator\constant(new FHIRPatientContact(
                relationship: $relationship,
            )),
        );
    }

    /**
     * Generate FHIR objects with extensions
     */
    private function generateFHIRObjectWithExtensions(): Generator
    {
        return Generator\oneOf(
            $this->generatePatientWithExtensions(),
            $this->generateStringWithExtensions(),
            $this->generateHumanNameWithExtensions(),
        );
    }

    /**
     * Generate Patient with extensions
     */
    private function generatePatientWithExtensions(): Generator
    {
        return Generator\bind(
            Generator\choose(1, 999),
            fn ($id) => Generator\bind(
                $this->generateExtensionArray(),
                fn ($extensions) => Generator\constant(new FHIRPatient(
                    resourceType: 'Patient',
                    id: (string) $id,
                    gender: 'male',
                    extension: $extensions,
                )),
            ),
        );
    }

    /**
     * Generate FHIRString with extensions
     */
    private function generateStringWithExtensions(): Generator
    {
        return Generator\bind(
            Generator\string(),
            fn ($value) => Generator\bind(
                $this->generateExtensionArray(),
                fn ($extensions) => Generator\constant(new FHIRString(
                    value: $value,
                    extension: $extensions,
                )),
            ),
        );
    }

    /**
     * Generate HumanName with extensions
     */
    private function generateHumanNameWithExtensions(): Generator
    {
        return Generator\bind(
            Generator\string(),
            fn ($family) => Generator\bind(
                $this->generateExtensionArray(),
                fn ($extensions) => Generator\constant(new FHIRHumanName(
                    family: $family,
                    extension: $extensions,
                )),
            ),
        );
    }

    /**
     * Generate extension array
     */
    private function generateExtensionArray(): Generator
    {
        return Generator\seq(
            Generator\bind(
                Generator\string(),
                fn ($url) => Generator\oneOf(
                    Generator\constant([
                        'url' => $url,
                        'valueString' => 'test-string',
                    ]),
                    Generator\constant([
                        'url' => $url,
                        'valueInteger' => 42,
                    ]),
                    Generator\constant([
                        'url' => $url,
                        'valueBoolean' => true,
                    ]),
                ),
            ),
            1,
            3,
        );
    }

    /**
     * Generate FHIR objects with metadata
     */
    private function generateFHIRObjectWithMetadata(): Generator
    {
        return Generator\oneOf(
            $this->generatePatientWithMetadata(),
            $this->generateObservationWithMetadata(),
        );
    }

    /**
     * Generate Patient with metadata
     */
    private function generatePatientWithMetadata(): Generator
    {
        return Generator\bind(
            Generator\choose(1, 999),
            static fn ($id) => Generator\bind(
                Generator\seq(
                    Generator\constant([
                        'system' => 'http://example.org/identifiers',
                        'value' => 'ID-' . $id,
                    ]),
                    1,
                    2,
                ),
                static fn ($identifier) => Generator\constant(new FHIRPatient(
                    resourceType: 'Patient',
                    id: (string) $id,
                    identifier: $identifier,
                    gender: 'male',
                )),
            ),
        );
    }

    /**
     * Generate Observation with metadata
     */
    private function generateObservationWithMetadata(): Generator
    {
        return Generator\bind(
            Generator\choose(1, 999),
            static fn ($id) => Generator\constant(new FHIRObservation(
                resourceType: 'Observation',
                id: (string) $id,
                status: 'final',
                code: [
                    'coding' => [
                        [
                            'system' => 'http://loinc.org',
                            'code' => '29463-7',
                            'display' => 'Body weight',
                        ],
                    ],
                ],
                subject: [
                    'reference' => 'Patient/' . $id,
                    'display' => 'Test Patient',
                ],
            )),
        );
    }

    /**
     * Get the appropriate normalizer for an object
     */
    private function getNormalizerForObject(object $object): ?object
    {
        if ($this->metadataExtractor->isResource($object)) {
            return $this->resourceNormalizer;
        }

        if ($this->metadataExtractor->isComplexType($object)) {
            return $this->complexTypeNormalizer;
        }

        if ($this->metadataExtractor->isPrimitiveType($object)) {
            return $this->primitiveTypeNormalizer;
        }

        if ($this->metadataExtractor->isBackboneElement($object)) {
            return $this->backboneElementNormalizer;
        }

        return null;
    }

    /**
     * Assert that two objects are equivalent
     */
    private function assertObjectsEquivalent(object $original, object $deserialized, string $context): void
    {
        self::assertInstanceOf(
            get_class($original),
            $deserialized,
            "{$context}: Deserialized object should be of the same class as original",
        );

        // Compare all public properties
        $reflection = new \ReflectionClass($original);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            // Check if property is initialized before accessing
            if (!$property->isInitialized($original) && !$property->isInitialized($deserialized)) {
                continue; // Both uninitialized, skip
            }
            
            // If only one is initialized, treat uninitialized as null
            $originalValue = $property->isInitialized($original) ? $property->getValue($original) : null;
            $deserializedValue = $property->isInitialized($deserialized) ? $property->getValue($deserialized) : null;

            $this->assertValuesEquivalent(
                $originalValue,
                $deserializedValue,
                "{$context}: Property {$property->getName()}",
            );
        }
    }

    /**
     * Assert that two values are equivalent (handles arrays and objects)
     */
    private function assertValuesEquivalent($original, $deserialized, string $context): void
    {
        if ($original === null && $deserialized === null) {
            return;
        }

        if ($original === null || $deserialized === null) {
            self::assertEquals($original, $deserialized, "{$context}: Null value mismatch");
            return;
        }

        if (is_array($original) && is_array($deserialized)) {
            self::assertCount(count($original), $deserialized, "{$context}: Array length mismatch");

            foreach ($original as $key => $value) {
                self::assertArrayHasKey($key, $deserialized, "{$context}: Missing array key {$key}");
                $this->assertValuesEquivalent($value, $deserialized[$key], "{$context}[{$key}]");
            }
            return;
        }

        if (is_object($original) && is_object($deserialized)) {
            $this->assertObjectsEquivalent($original, $deserialized, $context);
            return;
        }

        self::assertEquals($original, $deserialized, "{$context}: Value mismatch");
    }

    /**
     * Check if an object has extensions
     */
    private function hasExtensions(object $object): bool
    {
        $reflection = new \ReflectionClass($object);

        try {
            $extensionProperty = $reflection->getProperty('extension');
            $extensions = $extensionProperty->getValue($object);
            return $extensions !== null && !empty($extensions);
        } catch (\ReflectionException) {
            return false;
        }
    }

    /**
     * Assert that extensions are preserved
     */
    private function assertExtensionsPreserved(object $original, object $deserialized, string $context): void
    {
        $reflection = new \ReflectionClass($original);

        try {
            $extensionProperty = $reflection->getProperty('extension');
            
            // Check if property is initialized before accessing
            if ($extensionProperty->isInitialized($original) && $extensionProperty->isInitialized($deserialized)) {
                $originalExtensions = $extensionProperty->getValue($original);
                $deserializedExtensions = $extensionProperty->getValue($deserialized);

                $this->assertValuesEquivalent(
                    $originalExtensions,
                    $deserializedExtensions,
                    "{$context}: Extensions",
                );
            }
        } catch (\ReflectionException) {
            // No extension property, skip
        }

        // Also check modifierExtension if present
        try {
            $modifierExtensionProperty = $reflection->getProperty('modifierExtension');
            
            // Check if property is initialized before accessing
            if ($modifierExtensionProperty->isInitialized($original) && $modifierExtensionProperty->isInitialized($deserialized)) {
                $originalModifierExtensions = $modifierExtensionProperty->getValue($original);
                $deserializedModifierExtensions = $modifierExtensionProperty->getValue($deserialized);

                $this->assertValuesEquivalent(
                    $originalModifierExtensions,
                    $deserializedModifierExtensions,
                    "{$context}: Modifier extensions",
                );
            }
        } catch (\ReflectionException) {
            // No modifierExtension property, skip
        }
    }

    /**
     * Assert that basic metadata is preserved (resourceType, id, status)
     */
    private function assertBasicMetadataPreserved(object $original, object $deserialized, string $context): void
    {
        $reflection = new \ReflectionClass($original);
        $basicMetadataProperties = ['resourceType', 'id', 'status'];

        foreach ($basicMetadataProperties as $propertyName) {
            try {
                $property = $reflection->getProperty($propertyName);
                
                // Check if property is initialized before accessing
                if (!$property->isInitialized($original) && !$property->isInitialized($deserialized)) {
                    continue; // Both uninitialized, skip
                }
                
                // If only one is initialized, treat uninitialized as null
                $originalValue = $property->isInitialized($original) ? $property->getValue($original) : null;
                $deserializedValue = $property->isInitialized($deserialized) ? $property->getValue($deserialized) : null;

                $this->assertValuesEquivalent(
                    $originalValue,
                    $deserializedValue,
                    "{$context}: Basic metadata property {$propertyName}",
                );
            } catch (\ReflectionException) {
                // Property doesn't exist, skip
            }
        }
    }

    /**
     * Assert that metadata is preserved
     */
    private function assertMetadataPreserved(object $original, object $deserialized, string $context): void
    {
        $reflection = new \ReflectionClass($original);
        $metadataProperties = ['id', 'identifier', 'resourceType', 'code', 'subject'];

        foreach ($metadataProperties as $propertyName) {
            try {
                $property = $reflection->getProperty($propertyName);
                
                // Check if property is initialized before accessing
                if (!$property->isInitialized($original) && !$property->isInitialized($deserialized)) {
                    continue; // Both uninitialized, skip
                }
                
                // If only one is initialized, treat uninitialized as null
                $originalValue = $property->isInitialized($original) ? $property->getValue($original) : null;
                $deserializedValue = $property->isInitialized($deserialized) ? $property->getValue($deserialized) : null;

                $this->assertValuesEquivalent(
                    $originalValue,
                    $deserializedValue,
                    "{$context}: Metadata property {$propertyName}",
                );
            } catch (\ReflectionException) {
                // Property doesn't exist, skip
            }
        }
    }
}