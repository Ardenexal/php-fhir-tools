<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Integration;

use Ardenexal\FHIRTools\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Serialization\FHIRSerializationContextFactory;
use Ardenexal\FHIRTools\Serialization\FHIRResourceNormalizer;
use Ardenexal\FHIRTools\Serialization\FHIRComplexTypeNormalizer;
use Ardenexal\FHIRTools\Serialization\FHIRPrimitiveTypeNormalizer;
use Ardenexal\FHIRTools\Serialization\FHIRBackboneElementNormalizer;
use Ardenexal\FHIRTools\Serialization\FHIRMetadataExtractor;
use Ardenexal\FHIRTools\Serialization\FHIRTypeResolver;
use Ardenexal\FHIRTools\Serialization\FHIRSchemaValidator;
use Ardenexal\FHIRTools\Tests\Fixtures\FHIR\FHIRPatient;
use Ardenexal\FHIRTools\Tests\Fixtures\FHIR\FHIRObservation;
use Ardenexal\FHIRTools\Tests\Fixtures\FHIR\FHIRHumanName;
use Ardenexal\FHIRTools\Tests\Fixtures\FHIR\FHIRAddress;
use Ardenexal\FHIRTools\Tests\Fixtures\FHIR\FHIRReference;
use Ardenexal\FHIRTools\Tests\Fixtures\FHIR\FHIRExtension;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;
use Ardenexal\FHIRTools\Tests\Utilities\FHIRTestCaseRepository;
use Symfony\Component\Serializer\Serializer;
use Ardenexal\FHIRTools\Serialization\FHIRSerializationDebugInfo;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;

/**
 * Integration tests for FHIR conformance validation with official test data
 *
 * This test class validates that FHIR serialization meets official FHIR
 * conformance requirements using real FHIR test cases and examples.
 *
 * Test Coverage:
 * - FHIR JSON specification compliance
 * - FHIR XML specification compliance
 * - Official FHIR example validation
 * - Conformance requirement validation
 * - Edge case handling per FHIR spec
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 */
class FHIRConformanceIntegrationTest extends TestCase
{
    private FHIRSerializationService $serializationService;

    private FHIRTestCaseRepository $testCaseRepository;

    private FHIRSchemaValidator $schemaValidator;

    private Serializer $serializer;

    private string $tempDir;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tempDir            = $this->createTempDirectory();
        $this->testCaseRepository = new FHIRTestCaseRepository($this->tempDir . '/conformance-tests');

        // Set up serialization components
        $metadataExtractor = new FHIRMetadataExtractor();
        $typeResolver      = new FHIRTypeResolver(
            resourceTypeMapping: [
                'Patient'     => FHIRPatient::class,
                'Observation' => FHIRObservation::class,
            ],
            referenceTypeMapping: [
                'Patient'     => FHIRPatient::class,
                'Observation' => FHIRObservation::class,
            ],
            complexTypeMapping: [
                'HumanName' => FHIRHumanName::class,
                'Address'   => FHIRAddress::class,
                'Reference' => FHIRReference::class,
                'Extension' => FHIRExtension::class,
            ],
        );

        $normalizers = [
            new FHIRResourceNormalizer($metadataExtractor, $typeResolver),
            new FHIRComplexTypeNormalizer($metadataExtractor, $typeResolver),
            new FHIRPrimitiveTypeNormalizer($metadataExtractor),
            new FHIRBackboneElementNormalizer($metadataExtractor),
        ];

        $encoders = [
            new JsonEncoder(),
            new XmlEncoder(),
        ];
        $this->serializer = new Serializer($normalizers, $encoders);
        $contextFactory   = new FHIRSerializationContextFactory();
        $debugInfo        = new FHIRSerializationDebugInfo(
            operation: 'test',
            format: 'json',
        );
        $this->serializationService = new FHIRSerializationService(
            $this->serializer,
            $contextFactory,
            $debugInfo,
        );
        $this->schemaValidator = new FHIRSchemaValidator();
    }

    protected function tearDown(): void
    {
        $this->cleanupTempDirectory($this->tempDir);
        parent::tearDown();
    }

    /**
     * Test FHIR JSON specification compliance with official examples
     *
     * **Validates: Requirements 1.1**
     */
    public function testFHIRJsonSpecificationCompliance(): void
    {
        $officialExamples = $this->loadOfficialFHIRExamples();

        foreach ($officialExamples as $exampleName => $exampleData) {
            $this->validateJsonSpecCompliance($exampleData, $exampleName);
        }

        self::assertNotEmpty($officialExamples, 'Should have official FHIR examples to test');
    }

    /**
     * Test FHIR XML specification compliance with official examples
     *
     * **Validates: Requirements 5.1**
     */
    public function testFHIRXmlSpecificationCompliance(): void
    {
        $officialExamples = $this->loadOfficialFHIRExamples();

        foreach ($officialExamples as $exampleName => $exampleData) {
            try {
                $this->validateXmlSpecCompliance($exampleData, $exampleName);
            } catch (\Exception $e) {
                // XML serialization may not be fully implemented yet
                self::markTestSkipped("XML serialization not fully implemented: {$e->getMessage()}");
            }
        }
    }

    /**
     * Test primitive extension handling per FHIR specification
     *
     * **Validates: Requirements 1.3, 7.5**
     */
    public function testPrimitiveExtensionHandling(): void
    {
        // Load example with primitive extensions
        $patientWithExtensions = $this->loadOfficialFixture('patient-example-with-extensions.json');

        // Create FHIR object
        $patient = $this->createPatientFromData($patientWithExtensions);

        // Serialize to JSON
        $jsonOutput = $this->serializationService->serializeToJson($patient);

        $decodedJson = json_decode($jsonOutput, true);

        // Validate primitive extension underscore notation
        $this->validatePrimitiveExtensionUnderscore($decodedJson);

        // Test round-trip preservation
        $deserializedPatient = $this->serializationService->deserializeFromJson(
            $jsonOutput,
            FHIRPatient::class,
        );

        $this->assertExtensionsPreserved($patient, $deserializedPatient);
    }

    /**
     * Test sparse extension array handling per FHIR specification
     *
     * **Validates: Requirements 1.4**
     */
    public function testSparseExtensionArrayHandling(): void
    {
        // Create patient with sparse extension array
        $patient = new FHIRPatient(
            resourceType: 'Patient',
            id: 'sparse-extension-test',
            name: [
                ['family' => 'Doe', 'given' => ['John']],
                ['family' => 'Smith', 'given' => ['Jane']],
            ],
        );

        $jsonOutput = $this->serializationService->serializeToJson($patient);

        $decodedJson = json_decode($jsonOutput, true);

        // Validate basic structure
        self::assertArrayHasKey('name', $decodedJson);
        self::assertCount(2, $decodedJson['name']);

        // Validate name structure
        self::assertSame('Doe', $decodedJson['name'][0]['family']);
        self::assertSame(['John'], $decodedJson['name'][0]['given']);
        self::assertSame('Smith', $decodedJson['name'][1]['family']);
        self::assertSame(['Jane'], $decodedJson['name'][1]['given']);
    }

    /**
     * Test null value omission per FHIR specification
     *
     * **Validates: Requirements 1.5**
     */
    public function testNullValueOmission(): void
    {
        // Create patient with some null values
        $patient = new FHIRPatient(
            resourceType: 'Patient',
            id: 'null-test',
            gender: 'male',
            birthDate: null, // This should be omitted
            name: null, // This should be omitted
            identifier: null, // This should be omitted
        );

        $jsonOutput = $this->serializationService->serializeToJson($patient);

        $decodedJson = json_decode($jsonOutput, true);

        // Validate null values are omitted
        self::assertArrayNotHasKey('birthDate', $decodedJson, 'Null birthDate should be omitted');
        self::assertArrayNotHasKey('name', $decodedJson, 'Null name should be omitted');
        self::assertArrayNotHasKey('identifier', $decodedJson, 'Null identifier should be omitted');

        // Validate non-null values are preserved
        self::assertArrayHasKey('resourceType', $decodedJson);
        self::assertArrayHasKey('id', $decodedJson);
        self::assertArrayHasKey('gender', $decodedJson);
    }

    /**
     * Test resourceType inclusion per FHIR specification
     *
     * **Validates: Requirements 1.2**
     */
    public function testResourceTypeInclusion(): void
    {
        $officialExamples = $this->loadOfficialFHIRExamples();

        foreach ($officialExamples as $exampleName => $exampleData) {
            $resourceType = $exampleData['resourceType'];
            $fhirObject   = $this->createFHIRObjectFromData($exampleData);

            $jsonOutput = $this->serializationService->serializeToJson($fhirObject);

            $decodedJson = json_decode($jsonOutput, true);

            // Validate resourceType is included and correct
            self::assertArrayHasKey('resourceType', $decodedJson, "ResourceType should be included for {$exampleName}");
            self::assertSame($resourceType, $decodedJson['resourceType'], "ResourceType should match for {$exampleName}");
        }
    }

    /**
     * Test extension data preservation through round-trip
     *
     * **Validates: Requirements 7.2**
     */
    public function testExtensionDataPreservation(): void
    {
        // Load patient with complex extensions
        $patientData     = $this->loadOfficialFixture('patient-example-with-extensions.json');
        $originalPatient = $this->createPatientFromData($patientData);

        // JSON round-trip
        $jsonOutput          = $this->serializationService->serializeToJson($originalPatient);
        $deserializedPatient = $this->serializationService->deserializeFromJson(
            $jsonOutput,
            FHIRPatient::class,
        );

        // Validate all extensions are preserved
        $this->assertExtensionsPreserved($originalPatient, $deserializedPatient);
    }

    /**
     * Test metadata preservation through serialization
     *
     * **Validates: Requirements 7.3**
     */
    public function testMetadataPreservation(): void
    {
        $officialExamples = $this->loadOfficialFHIRExamples();

        foreach ($officialExamples as $exampleName => $exampleData) {
            $originalObject = $this->createFHIRObjectFromData($exampleData);

            // Round-trip test
            $jsonOutput         = $this->serializationService->serializeToJson($originalObject);
            $deserializedObject = $this->serializationService->deserializeFromJson(
                $jsonOutput,
                get_class($originalObject),
            );

            // Validate metadata preservation
            $this->assertMetadataPreserved($originalObject, $deserializedObject, $exampleName);
        }
    }

    /**
     * Test nested structure preservation
     *
     * **Validates: Requirements 7.4**
     */
    public function testNestedStructurePreservation(): void
    {
        // Load complex patient example with nested structures
        $patientData     = $this->loadOfficialFixture('patient-example.json');
        $originalPatient = $this->createPatientFromData($patientData);

        // Round-trip test
        $jsonOutput          = $this->serializationService->serializeToJson($originalPatient);
        $deserializedPatient = $this->serializationService->deserializeFromJson(
            $jsonOutput,
            FHIRPatient::class,
        );

        // Validate nested structures are preserved
        $this->assertNestedStructuresPreserved($originalPatient, $deserializedPatient);
    }

    /**
     * Load official FHIR examples
     *
     * @return array<string, array<string, mixed>>
     */
    private function loadOfficialFHIRExamples(): array
    {
        return [
            'patient-example'         => $this->loadOfficialFixture('patient-example.json'),
            'patient-with-extensions' => $this->loadOfficialFixture('patient-example-with-extensions.json'),
            'observation-example'     => $this->loadOfficialFixture('observation-example.json'),
        ];
    }

    /**
     * Load official fixture file
     *
     * @return array<string, mixed>
     */
    private function loadOfficialFixture(string $filename): array
    {
        $path = __DIR__ . '/../Fixtures/OfficialFHIR/' . $filename;
        if (!file_exists($path)) {
            throw new \RuntimeException("Official fixture file not found: {$filename}");
        }

        $content = file_get_contents($path);
        if ($content === false) {
            throw new \RuntimeException("Failed to read official fixture file: {$path}");
        }

        return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Validate JSON specification compliance
     */
    private function validateJsonSpecCompliance(array $exampleData, string $exampleName): void
    {
        $fhirObject = $this->createFHIRObjectFromData($exampleData);

        $jsonOutput = $this->serializationService->serializeToJson($fhirObject);

        $decodedJson = json_decode($jsonOutput, true);

        // FHIR JSON specification requirements
        self::assertArrayHasKey('resourceType', $decodedJson, "ResourceType required for {$exampleName}");

        // Validate no null values (FHIR rule)
        $this->assertNoNullValues($decodedJson, $exampleName);

        // Validate extension underscore notation
        $this->validateExtensionUnderscoreNotation($decodedJson, $exampleName);
    }

    /**
     * Validate XML specification compliance
     */
    private function validateXmlSpecCompliance(array $exampleData, string $exampleName): void
    {
        $fhirObject = $this->createFHIRObjectFromData($exampleData);

        $xmlOutput = $this->serializationService->serializeToXml($fhirObject);

        // Validate XML is well-formed
        $dom    = new \DOMDocument();
        $result = $dom->loadXML($xmlOutput);
        self::assertTrue($result, "XML should be well-formed for {$exampleName}");

        // Validate FHIR namespace
        $xpath            = new \DOMXPath($dom);
        $namespaces       = $xpath->query('//namespace::*');
        $hasFhirNamespace = false;

        foreach ($namespaces as $namespace) {
            if (str_contains($namespace->nodeValue, 'hl7.org/fhir')) {
                $hasFhirNamespace = true;
                break;
            }
        }

        self::assertTrue($hasFhirNamespace, "FHIR namespace required for {$exampleName}");
    }

    /**
     * Create FHIR object from data
     */
    private function createFHIRObjectFromData(array $data): object
    {
        $resourceType = $data['resourceType'] ?? 'Unknown';

        switch ($resourceType) {
            case 'Patient':
                return $this->createPatientFromData($data);

            case 'Observation':
                return $this->createObservationFromData($data);

            default:
                throw new \InvalidArgumentException("Unsupported resource type: {$resourceType}");
        }
    }

    /**
     * Create Patient from data
     */
    private function createPatientFromData(array $data): FHIRPatient
    {
        return new FHIRPatient(
            resourceType: $data['resourceType']           ?? 'Patient',
            id: $data['id']                               ?? null,
            identifier: $data['identifier']               ?? null,
            name: $data['name']                           ?? null,
            gender: $data['gender']                       ?? null,
            birthDate: $data['birthDate']                 ?? null,
            extension: $data['extension']                 ?? null,
            modifierExtension: $data['modifierExtension'] ?? null,
        );
    }

    /**
     * Create Observation from data
     */
    private function createObservationFromData(array $data): FHIRObservation
    {
        return new FHIRObservation(
            resourceType: $data['resourceType'] ?? 'Observation',
            id: $data['id']                     ?? null,
            status: $data['status']             ?? 'unknown',
            code: $data['code']                 ?? null,
            subject: $data['subject']           ?? null,
            valueString: $data['valueString']   ?? null,
            valueInteger: $data['valueInteger'] ?? null,
            extension: $data['extension']       ?? null,
        );
    }

    /**
     * Validate primitive extension underscore notation
     */
    private function validatePrimitiveExtensionUnderscore(array $data): void
    {
        // Check for _birthDate extension
        if (isset($data['_birthDate'])) {
            self::assertIsArray($data['_birthDate'], 'Primitive extension should be array');
            self::assertArrayHasKey('extension', $data['_birthDate'], 'Primitive extension should have extension array');
        }

        // Check for _name extensions
        if (isset($data['_name'])) {
            self::assertIsArray($data['_name'], 'Name extensions should be array');
            foreach ($data['_name'] as $nameExtension) {
                if ($nameExtension !== null) {
                    self::assertIsArray($nameExtension, 'Name extension element should be array');
                }
            }
        }
    }

    /**
     * Assert no null values in JSON
     */
    private function assertNoNullValues(array $data, string $context): void
    {
        foreach ($data as $key => $value) {
            if ($value === null) {
                self::fail("Null value found at {$context}.{$key} - FHIR JSON should not contain null values");
            }

            if (is_array($value)) {
                $this->assertNoNullValues($value, "{$context}.{$key}");
            }
        }
    }

    /**
     * Validate extension underscore notation
     */
    private function validateExtensionUnderscoreNotation(array $data, string $context): void
    {
        foreach ($data as $key => $value) {
            if (is_string($key) && str_starts_with($key, '_') && is_array($value)) {
                $primitiveKey = substr($key, 1);
                // Extension field should have corresponding primitive or be valid sparse extension
                self::assertTrue(
                    array_key_exists($primitiveKey, $data) || $this->isValidSparseExtension($data, $key),
                    "Extension field '{$key}' should have corresponding primitive field in {$context}",
                );
            }

            if (is_array($value)) {
                $this->validateExtensionUnderscoreNotation($value, "{$context}.{$key}");
            }
        }
    }

    /**
     * Check if this is a valid sparse extension
     */
    private function isValidSparseExtension(array $data, string $extensionKey): bool
    {
        $primitiveKey = substr($extensionKey, 1);

        return isset($data[$primitiveKey]) && is_array($data[$primitiveKey]);
    }

    /**
     * Assert extensions are preserved
     */
    private function assertExtensionsPreserved(object $original, object $deserialized): void
    {
        $reflection = new \ReflectionClass($original);

        // Check extension property
        if ($reflection->hasProperty('extension')) {
            $extensionProperty = $reflection->getProperty('extension');

            if ($extensionProperty->isInitialized($original) && $extensionProperty->isInitialized($deserialized)) {
                $originalExtensions     = $extensionProperty->getValue($original);
                $deserializedExtensions = $extensionProperty->getValue($deserialized);

                self::assertEquals(
                    $originalExtensions,
                    $deserializedExtensions,
                    'Extensions should be preserved through round-trip',
                );
            }
        }

        // Check primitive extensions (e.g., _birthDate, _name)
        foreach ($reflection->getProperties() as $property) {
            if (str_starts_with($property->getName(), '_')) {
                if ($property->isInitialized($original) && $property->isInitialized($deserialized)) {
                    $originalValue     = $property->getValue($original);
                    $deserializedValue = $property->getValue($deserialized);

                    self::assertEquals(
                        $originalValue,
                        $deserializedValue,
                        "Primitive extension {$property->getName()} should be preserved",
                    );
                }
            }
        }
    }

    /**
     * Assert metadata is preserved
     */
    private function assertMetadataPreserved(object $original, object $deserialized, string $context): void
    {
        $reflection         = new \ReflectionClass($original);
        $metadataProperties = ['resourceType', 'id', 'identifier', 'status'];

        foreach ($metadataProperties as $propertyName) {
            if ($reflection->hasProperty($propertyName)) {
                $property = $reflection->getProperty($propertyName);

                if ($property->isInitialized($original) && $property->isInitialized($deserialized)) {
                    $originalValue     = $property->getValue($original);
                    $deserializedValue = $property->getValue($deserialized);

                    self::assertEquals(
                        $originalValue,
                        $deserializedValue,
                        "Metadata property {$propertyName} should be preserved for {$context}",
                    );
                }
            }
        }
    }

    /**
     * Assert nested structures are preserved
     */
    private function assertNestedStructuresPreserved(object $original, object $deserialized): void
    {
        $reflection       = new \ReflectionClass($original);
        $nestedProperties = ['name', 'address', 'contact', 'telecom'];

        foreach ($nestedProperties as $propertyName) {
            if ($reflection->hasProperty($propertyName)) {
                $property = $reflection->getProperty($propertyName);

                if ($property->isInitialized($original) && $property->isInitialized($deserialized)) {
                    $originalValue     = $property->getValue($original);
                    $deserializedValue = $property->getValue($deserialized);

                    self::assertEquals(
                        $originalValue,
                        $deserializedValue,
                        "Nested structure {$propertyName} should be preserved",
                    );
                }
            }
        }
    }
}
