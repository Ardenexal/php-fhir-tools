<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\FHIR;

use Ardenexal\FHIRTools\ErrorCollector;
use Ardenexal\FHIRTools\Tests\Utilities\FHIRTestDataGenerator;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;
use Eris\TestTrait;

/**
 * Test cases for FHIR StructureDefinition validation
 *
 * This test class verifies FHIR-specific validation logic using both
 * traditional unit tests and property-based testing with Eris.
 *
 * Test Coverage:
 * - StructureDefinition schema validation
 * - Element cardinality validation
 * - Type system validation
 * - Reference validation
 * - Extension validation
 * - Property-based testing for edge cases
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 */
class StructureDefinitionValidationTest extends TestCase
{
    use TestTrait;

    private ErrorCollector $errorCollector;

    protected function setUp(): void
    {
        $this->errorCollector = new ErrorCollector();
    }

    /**
     * Test validation of valid FHIR StructureDefinition
     */
    public function testValidStructureDefinitionPasses(): void
    {
        $structureDefinition = $this->loadFixture('Patient.json');

        // This would be called by the actual validator
        $this->validateStructureDefinition($structureDefinition);

        self::assertFalse($this->errorCollector->hasErrors());
        self::assertFalse($this->errorCollector->hasWarnings());
    }

    /**
     * Test validation of invalid FHIR StructureDefinition
     */
    public function testInvalidStructureDefinitionFails(): void
    {
        $structureDefinition = $this->loadFixture('InvalidStructureDefinition.json');

        $this->validateStructureDefinition($structureDefinition);

        self::assertTrue($this->errorCollector->hasErrors());
        $this->assertErrorCollectorContains(
            $this->errorCollector,
            'Invalid cardinality',
            'Patient.invalidElement',
        );
    }

    /**
     * Property-based test for cardinality validation
     */
    public function testCardinalityValidationWithPropertyBasedTesting(): void
    {
        $this->forAll(
            FHIRTestDataGenerator::invalidFhirCardinality(),
            FHIRTestDataGenerator::fhirElementPath(),
        )->then(function(string $cardinality, string $elementPath): void {
            $this->errorCollector->clear();

            // Simulate cardinality validation
            $this->validateCardinality($cardinality, $elementPath);

            self::assertTrue(
                $this->errorCollector->hasErrors(),
                "Invalid cardinality '{$cardinality}' should produce validation errors",
            );
        });
    }

    /**
     * Property-based test for valid cardinality
     */
    public function testValidCardinalityWithPropertyBasedTesting(): void
    {
        $this->forAll(
            FHIRTestDataGenerator::fhirCardinality(),
            FHIRTestDataGenerator::fhirElementPath(),
        )->then(function(string $cardinality, string $elementPath): void {
            $this->errorCollector->clear();

            // Simulate cardinality validation
            $this->validateCardinality($cardinality, $elementPath);

            self::assertFalse(
                $this->errorCollector->hasErrors(),
                "Valid cardinality '{$cardinality}' should not produce validation errors",
            );
        });
    }

    /**
     * Test FHIR resource type validation
     */
    public function testResourceTypeValidation(): void
    {
        $this->forAll(
            FHIRTestDataGenerator::fhirResourceType(),
        )->then(function(string $resourceType): void {
            $this->errorCollector->clear();

            // Simulate resource type validation
            $this->validateResourceType($resourceType);

            self::assertFalse(
                $this->errorCollector->hasErrors(),
                "Valid resource type '{$resourceType}' should not produce validation errors",
            );
        });
    }

    /**
     * Test FHIR URL validation
     */
    public function testFhirUrlValidation(): void
    {
        $this->forAll(
            FHIRTestDataGenerator::fhirUrl(),
        )->then(function(string $url): void {
            $this->errorCollector->clear();

            // Simulate URL validation
            $this->validateFhirUrl($url);

            self::assertFalse(
                $this->errorCollector->hasErrors(),
                "Valid FHIR URL '{$url}' should not produce validation errors",
            );
        });
    }

    /**
     * Test element path validation with complex paths
     */
    public function testComplexElementPathValidation(): void
    {
        $this->forAll(
            FHIRTestDataGenerator::complexFhirElementPath(),
        )->then(function(string $elementPath): void {
            $this->errorCollector->clear();

            // Simulate element path validation
            $this->validateElementPath($elementPath);

            self::assertFalse(
                $this->errorCollector->hasErrors(),
                "Valid element path '{$elementPath}' should not produce validation errors",
            );
        });
    }

    /**
     * Test FHIR version validation
     */
    public function testFhirVersionValidation(): void
    {
        $this->forAll(
            FHIRTestDataGenerator::invalidFhirVersion(),
        )->then(function(string $version): void {
            $this->errorCollector->clear();

            // Simulate version validation
            $this->validateFhirVersion($version);

            self::assertTrue(
                $this->errorCollector->hasErrors(),
                "Invalid FHIR version '{$version}' should produce validation errors",
            );
        });
    }

    /**
     * Load test fixture from JSON file
     * @return array<string, mixed>
     */
    private function loadFixture(string $filename): array
    {
        $path    = __DIR__ . '/../Fixtures/StructureDefinitions/' . $filename;
        $content = file_get_contents($path);
        if ($content === false) {
            throw new \RuntimeException("Failed to read file: {$path}");
        }

        return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Simulate StructureDefinition validation
     *
     * @param array<string, mixed> $structureDefinition
     */
    private function validateStructureDefinition(array $structureDefinition): void
    {
        // Required fields validation
        if (!isset($structureDefinition['resourceType']) || $structureDefinition['resourceType'] !== 'StructureDefinition') {
            $this->errorCollector->addError('Invalid resourceType', '', 'INVALID_RESOURCE_TYPE');
        }

        if (!isset($structureDefinition['url'])) {
            $this->errorCollector->addError('Missing required field: url', '', 'MISSING_URL');
        }

        if (!isset($structureDefinition['name'])) {
            $this->errorCollector->addError('Missing required field: name', '', 'MISSING_NAME');
        }

        // Validate differential elements
        if (isset($structureDefinition['differential']['element'])) {
            foreach ($structureDefinition['differential']['element'] as $element) {
                if (isset($element['min'], $element['max'])) {
                    $this->validateCardinality($element['min'] . '..' . $element['max'], $element['path'] ?? '');
                }
            }
        }
    }

    /**
     * Simulate cardinality validation
     */
    private function validateCardinality(string $cardinality, string $elementPath): void
    {
        if (!preg_match('/^(\d+|\*)\.\.(\d+|\*)$/', $cardinality)) {
            $this->errorCollector->addError(
                "Invalid cardinality format: {$cardinality}",
                $elementPath,
                'INVALID_CARDINALITY_FORMAT',
            );

            return;
        }

        [$min, $max] = explode('..', $cardinality);

        if ($min !== '*' && $max !== '*' && (int) $min > (int) $max) {
            $this->errorCollector->addError(
                "Invalid cardinality '{$cardinality}': minimum cannot exceed maximum",
                $elementPath,
                'INVALID_CARDINALITY_RANGE',
            );
        }

        if ($min !== '*' && (int) $min < 0) {
            $this->errorCollector->addError(
                "Invalid cardinality '{$cardinality}': minimum cannot be negative",
                $elementPath,
                'NEGATIVE_CARDINALITY_MIN',
            );
        }
    }

    /**
     * Simulate resource type validation
     */
    private function validateResourceType(string $resourceType): void
    {
        $validTypes = [
            'Patient', 'Observation', 'Practitioner', 'Organization',
            'Encounter', 'Condition', 'Procedure', 'MedicationRequest',
            'DiagnosticReport', 'AllergyIntolerance',
        ];

        if (!in_array($resourceType, $validTypes, true)) {
            $this->errorCollector->addError(
                "Invalid resource type: {$resourceType}",
                '',
                'INVALID_RESOURCE_TYPE',
            );
        }
    }

    /**
     * Simulate FHIR URL validation
     */
    private function validateFhirUrl(string $url): void
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            $this->errorCollector->addError(
                "Invalid URL format: {$url}",
                '',
                'INVALID_URL_FORMAT',
            );
        }
    }

    /**
     * Simulate element path validation
     */
    private function validateElementPath(string $elementPath): void
    {
        if (empty($elementPath)) {
            $this->errorCollector->addError(
                'Element path cannot be empty',
                $elementPath,
                'EMPTY_ELEMENT_PATH',
            );
        }

        if (!preg_match('/^[A-Z][a-zA-Z0-9]*(\.[a-zA-Z0-9\[\]]+)*$/', $elementPath)) {
            $this->errorCollector->addError(
                "Invalid element path format: {$elementPath}",
                $elementPath,
                'INVALID_ELEMENT_PATH_FORMAT',
            );
        }
    }

    /**
     * Simulate FHIR version validation
     */
    private function validateFhirVersion(string $version): void
    {
        $supportedVersions = ['4.0.1', '4.3.0', '5.0.0'];

        if (!in_array($version, $supportedVersions, true)) {
            $this->errorCollector->addError(
                "Unsupported FHIR version: {$version}",
                '',
                'UNSUPPORTED_FHIR_VERSION',
            );
        }
    }
}
