<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\Models;

use Ardenexal\FHIRTools\Component\Models\Utility\ModelRegistry;
use Ardenexal\FHIRTools\Component\Models\Utility\VersionDetector;
use Eris\Generator;
use Eris\TestTrait;
use PHPUnit\Framework\TestCase;

/**
 * Property-based test for R4B model completeness.
 *
 * **Feature: fhir-models-component, Property 1: Version-specific namespace isolation (R4B)**
 *
 * Tests that R4B models are properly organized in version-specific namespaces
 * and maintain isolation from other FHIR versions.
 */
class R4BModelCompletenessPropertyTest extends TestCase
{
    use TestTrait;

    /**
     * Test that R4B resources are properly isolated in version-specific namespaces.
     *
     * **Feature: fhir-models-component, Property 1: Version-specific namespace isolation (R4B)**
     * **Validates: Requirements 8.4**
     *
     * Property: For any R4B resource name, the generated class should be in the
     * Ardenexal\FHIRTools\Component\Models\R4B\Resource namespace and isolated from other versions.
     */
    public function testR4BResourceNamespaceIsolation(): void
    {
        $this->forAll(
            Generator\elements([
                'Patient', 'Observation', 'Practitioner', 'Organization', 'Encounter',
                'Condition', 'Procedure', 'MedicationRequest', 'DiagnosticReport',
                'Appointment', 'Account', 'Basic', 'Binary', 'Bundle', 'Claim',
                'Communication', 'Composition', 'Consent', 'Contract', 'Coverage',
                'Device', 'Endpoint', 'Evidence', 'Flag', 'Goal', 'Group',
                'Immunization', 'Invoice', 'Library', 'Linkage', 'List', 'Location',
                'Measure', 'Media', 'Medication', 'Provenance', 'Questionnaire',
                'Schedule', 'Specimen', 'Subscription', 'Substance', 'Task',
            ]),
        )->then(function(string $resourceName): void {
            $className = ModelRegistry::getResourceClass('R4B', $resourceName);

            // Verify the class is in the correct R4B Resource namespace
            self::assertStringContainsString('\\Component\\Models\\R4B\\Resource\\', $className);
            self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R4B\\Resource\\FHIR' . $resourceName, $className);

            // Verify version detection works correctly
            $detectedVersion = VersionDetector::detectVersionFromClassName($className);
            self::assertEquals('R4B', $detectedVersion);

            // Verify it's identified as a Models component class
            self::assertTrue(VersionDetector::isModelsComponentClass($className));

            // Verify model type detection
            $modelType = VersionDetector::getModelType($className);
            self::assertEquals('Resource', $modelType);

            // Verify version-specific class detection
            self::assertTrue(VersionDetector::isVersionSpecificClass($className, 'R4B'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R4'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R5'));

            // Verify isolation from other versions
            $r4ClassName = ModelRegistry::getResourceClass('R4', $resourceName);
            $r5ClassName = ModelRegistry::getResourceClass('R5', $resourceName);

            self::assertNotEquals($className, $r4ClassName);
            self::assertNotEquals($className, $r5ClassName);
            self::assertStringContainsString('\\R4\\Resource\\', $r4ClassName);
            self::assertStringContainsString('\\R5\\Resource\\', $r5ClassName);
        });
    }

    /**
     * Test that R4B data types are properly isolated in version-specific namespaces.
     *
     * **Feature: fhir-models-component, Property 1: Version-specific namespace isolation (R4B)**
     * **Validates: Requirements 8.4**
     *
     * Property: For any R4B data type name, the generated class should be in the
     * Ardenexal\FHIRTools\Component\Models\R4B\DataType namespace and isolated from other versions.
     */
    public function testR4BDataTypeNamespaceIsolation(): void
    {
        $this->forAll(
            Generator\elements([
                'HumanName', 'Address', 'ContactPoint', 'Identifier', 'Coding',
                'CodeableConcept', 'Quantity', 'Range', 'Ratio', 'Period',
                'Attachment', 'Annotation', 'Signature', 'Reference', 'Meta',
                'Narrative', 'Extension', 'Age', 'Count', 'Distance', 'Duration',
                'Money', 'Timing', 'Dosage', 'Expression', 'Contributor',
            ]),
        )->then(function(string $dataTypeName): void {
            $className = ModelRegistry::getDataTypeClass('R4B', $dataTypeName);

            // Verify the class is in the correct R4B DataType namespace
            self::assertStringContainsString('\\Component\\Models\\R4B\\DataType\\', $className);
            self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R4B\\DataType\\FHIR' . $dataTypeName, $className);

            // Verify version detection works correctly
            $detectedVersion = VersionDetector::detectVersionFromClassName($className);
            self::assertEquals('R4B', $detectedVersion);

            // Verify it's identified as a Models component class
            self::assertTrue(VersionDetector::isModelsComponentClass($className));

            // Verify model type detection
            $modelType = VersionDetector::getModelType($className);
            self::assertEquals('DataType', $modelType);

            // Verify version-specific class detection
            self::assertTrue(VersionDetector::isVersionSpecificClass($className, 'R4B'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R4'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R5'));

            // Verify isolation from other versions
            $r4ClassName = ModelRegistry::getDataTypeClass('R4', $dataTypeName);
            $r5ClassName = ModelRegistry::getDataTypeClass('R5', $dataTypeName);

            self::assertNotEquals($className, $r4ClassName);
            self::assertNotEquals($className, $r5ClassName);
            self::assertStringContainsString('\\R4\\DataType\\', $r4ClassName);
            self::assertStringContainsString('\\R5\\DataType\\', $r5ClassName);
        });
    }

    /**
     * Test that R4B primitive types are properly isolated in version-specific namespaces.
     *
     * **Feature: fhir-models-component, Property 1: Version-specific namespace isolation (R4B)**
     * **Validates: Requirements 8.4**
     *
     * Property: For any R4B primitive type name, the generated class should be in the
     * Ardenexal\FHIRTools\Component\Models\R4B\Primitive namespace and isolated from other versions.
     */
    public function testR4BPrimitiveTypeNamespaceIsolation(): void
    {
        $this->forAll(
            Generator\elements([
                'String', 'Integer', 'Boolean', 'Decimal', 'DateTime', 'Date',
                'Time', 'Instant', 'Uri', 'Url', 'Canonical', 'Base64Binary',
                'Code', 'Id', 'Markdown', 'Oid', 'PositiveInt', 'UnsignedInt',
                'Uuid', 'Xhtml',
            ]),
        )->then(function(string $primitiveName): void {
            $className = ModelRegistry::getPrimitiveClass('R4B', $primitiveName);

            // Verify the class is in the correct R4B Primitive namespace
            self::assertStringContainsString('\\Component\\Models\\R4B\\Primitive\\', $className);
            self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R4B\\Primitive\\FHIR' . $primitiveName, $className);

            // Verify version detection works correctly
            $detectedVersion = VersionDetector::detectVersionFromClassName($className);
            self::assertEquals('R4B', $detectedVersion);

            // Verify it's identified as a Models component class
            self::assertTrue(VersionDetector::isModelsComponentClass($className));

            // Verify model type detection
            $modelType = VersionDetector::getModelType($className);
            self::assertEquals('Primitive', $modelType);

            // Verify version-specific class detection
            self::assertTrue(VersionDetector::isVersionSpecificClass($className, 'R4B'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R4'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R5'));

            // Verify isolation from other versions
            $r4ClassName = ModelRegistry::getPrimitiveClass('R4', $primitiveName);
            $r5ClassName = ModelRegistry::getPrimitiveClass('R5', $primitiveName);

            self::assertNotEquals($className, $r4ClassName);
            self::assertNotEquals($className, $r5ClassName);
            self::assertStringContainsString('\\R4\\Primitive\\', $r4ClassName);
            self::assertStringContainsString('\\R5\\Primitive\\', $r5ClassName);
        });
    }

    /**
     * Test that R4B enums are properly isolated in version-specific namespaces.
     *
     * **Feature: fhir-models-component, Property 1: Version-specific namespace isolation (R4B)**
     * **Validates: Requirements 8.4**
     *
     * Property: For any R4B enum name, the generated class should be in the
     * Ardenexal\FHIRTools\Component\Models\R4B\Enum namespace and isolated from other versions.
     */
    public function testR4BEnumNamespaceIsolation(): void
    {
        $this->forAll(
            Generator\elements([
                'AdministrativeGender', 'ObservationStatus', 'ContactPointSystem',
                'ContactPointUse', 'AddressType', 'AddressUse', 'NameUse',
                'IdentifierUse', 'AppointmentStatus', 'ParticipationStatus',
                'EncounterStatus', 'LocationStatus', 'BundleType', 'CompositionStatus',
                'DocumentReferenceStatus', 'AllergyIntoleranceCategory', 'AllergyIntoleranceCriticality',
                'ConditionClinicalStatusCodes', 'ConditionVerificationStatus', 'DiagnosticReportStatus',
                'MedicationRequestStatus', 'MedicationRequestIntent', 'TaskStatus', 'TaskIntent',
            ]),
        )->then(function(string $enumName): void {
            $className = ModelRegistry::getEnumClass('R4B', $enumName);

            // Verify the class is in the correct R4B Enum namespace
            self::assertStringContainsString('\\Component\\Models\\R4B\\Enum\\', $className);
            self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R4B\\Enum\\FHIR' . $enumName, $className);

            // Verify version detection works correctly
            $detectedVersion = VersionDetector::detectVersionFromClassName($className);
            self::assertEquals('R4B', $detectedVersion);

            // Verify it's identified as a Models component class
            self::assertTrue(VersionDetector::isModelsComponentClass($className));

            // Verify model type detection
            $modelType = VersionDetector::getModelType($className);
            self::assertEquals('Enum', $modelType);

            // Verify version-specific class detection
            self::assertTrue(VersionDetector::isVersionSpecificClass($className, 'R4B'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R4'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R5'));

            // Verify isolation from other versions
            $r4ClassName = ModelRegistry::getEnumClass('R4', $enumName);
            $r5ClassName = ModelRegistry::getEnumClass('R5', $enumName);

            self::assertNotEquals($className, $r4ClassName);
            self::assertNotEquals($className, $r5ClassName);
            self::assertStringContainsString('\\R4\\Enum\\', $r4ClassName);
            self::assertStringContainsString('\\R5\\Enum\\', $r5ClassName);
        });
    }

    /**
     * Test that R4B backbone elements are properly isolated in version-specific namespaces.
     *
     * **Feature: fhir-models-component, Property 1: Version-specific namespace isolation (R4B)**
     * **Validates: Requirements 8.4**
     *
     * Property: For any R4B backbone element, the generated class should be in the
     * appropriate resource subdirectory within the R4B namespace and isolated from other versions.
     */
    public function testR4BBackboneElementNamespaceIsolation(): void
    {
        $this->forAll(
            Generator\elements([
                ['Patient', 'Contact'],
                ['Patient', 'Communication'],
                ['Patient', 'Link'],
                ['Observation', 'Component'],
                ['Observation', 'ReferenceRange'],
                ['Bundle', 'Entry'],
                ['Bundle', 'Link'],
                ['Composition', 'Attester'],
                ['Composition', 'Event'],
                ['Composition', 'Section'],
                ['Encounter', 'StatusHistory'],
                ['Encounter', 'ClassHistory'],
                ['Encounter', 'Participant'],
                ['Encounter', 'Diagnosis'],
                ['Encounter', 'Hospitalization'],
                ['Encounter', 'Location'],
            ]),
        )->then(function(array $backboneInfo): void {
            [$resourceName, $elementName] = $backboneInfo;
            $className                    = ModelRegistry::getBackboneElementClass('R4B', $resourceName, $elementName);

            // Verify the class is in the correct R4B Resource subdirectory
            self::assertStringContainsString("\\Component\\Models\\R4B\\Resource\\{$resourceName}\\", $className);
            self::assertEquals("Ardenexal\\FHIRTools\\Component\\Models\\R4B\\Resource\\{$resourceName}\\FHIR{$resourceName}{$elementName}", $className);

            // Verify version detection works correctly
            $detectedVersion = VersionDetector::detectVersionFromClassName($className);
            self::assertEquals('R4B', $detectedVersion);

            // Verify it's identified as a Models component class
            self::assertTrue(VersionDetector::isModelsComponentClass($className));

            // Verify version-specific class detection
            self::assertTrue(VersionDetector::isVersionSpecificClass($className, 'R4B'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R4'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R5'));

            // Verify isolation from other versions
            $r4ClassName = ModelRegistry::getBackboneElementClass('R4', $resourceName, $elementName);
            $r5ClassName = ModelRegistry::getBackboneElementClass('R5', $resourceName, $elementName);

            self::assertNotEquals($className, $r4ClassName);
            self::assertNotEquals($className, $r5ClassName);
            self::assertStringContainsString("\\R4\\Resource\\{$resourceName}\\", $r4ClassName);
            self::assertStringContainsString("\\R5\\Resource\\{$resourceName}\\", $r5ClassName);
        });
    }

    /**
     * Test that R4B code type wrappers are properly isolated in version-specific namespaces.
     *
     * **Feature: fhir-models-component, Property 1: Version-specific namespace isolation (R4B)**
     * **Validates: Requirements 8.4**
     *
     * Property: For any R4B enum with a code type wrapper, the generated class should be in the
     * Ardenexal\FHIRTools\Component\Models\R4B\DataType namespace and isolated from other versions.
     */
    public function testR4BCodeTypeNamespaceIsolation(): void
    {
        $this->forAll(
            Generator\elements([
                'AdministrativeGender', 'ObservationStatus', 'ContactPointSystem',
                'ContactPointUse', 'AddressType', 'AddressUse', 'NameUse',
                'IdentifierUse', 'AppointmentStatus', 'ParticipationStatus',
                'EncounterStatus', 'LocationStatus', 'BundleType', 'CompositionStatus',
            ]),
        )->then(function(string $enumName): void {
            $className = ModelRegistry::getCodeTypeClass('R4B', $enumName);

            // Verify the class is in the correct R4B DataType namespace (code types are data types)
            self::assertStringContainsString('\\Component\\Models\\R4B\\DataType\\', $className);
            self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R4B\\DataType\\FHIR' . $enumName . 'Type', $className);

            // Verify version detection works correctly
            $detectedVersion = VersionDetector::detectVersionFromClassName($className);
            self::assertEquals('R4B', $detectedVersion);

            // Verify it's identified as a Models component class
            self::assertTrue(VersionDetector::isModelsComponentClass($className));

            // Verify model type detection (code types are in DataType namespace)
            $modelType = VersionDetector::getModelType($className);
            self::assertEquals('DataType', $modelType);

            // Verify version-specific class detection
            self::assertTrue(VersionDetector::isVersionSpecificClass($className, 'R4B'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R4'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R5'));

            // Verify isolation from other versions
            $r4ClassName = ModelRegistry::getCodeTypeClass('R4', $enumName);
            $r5ClassName = ModelRegistry::getCodeTypeClass('R5', $enumName);

            self::assertNotEquals($className, $r4ClassName);
            self::assertNotEquals($className, $r5ClassName);
            self::assertStringContainsString('\\R4\\DataType\\', $r4ClassName);
            self::assertStringContainsString('\\R5\\DataType\\', $r5ClassName);
        });
    }

    /**
     * Test that R4B namespace organization is consistent across all model types.
     *
     * **Feature: fhir-models-component, Property 1: Version-specific namespace isolation (R4B)**
     * **Validates: Requirements 8.4**
     *
     * Property: For any R4B model type and name combination, the namespace organization
     * should be consistent and follow the established pattern.
     */
    public function testR4BNamespaceOrganizationConsistency(): void
    {
        $this->forAll(
            Generator\elements([
                ['Resource', 'Patient'],
                ['DataType', 'HumanName'],
                ['Primitive', 'String'],
                ['Enum', 'AdministrativeGender'],
            ]),
        )->then(function(array $modelInfo): void {
            [$modelType, $modelName] = $modelInfo;

            $className = match ($modelType) {
                'Resource'  => ModelRegistry::getResourceClass('R4B', $modelName),
                'DataType'  => ModelRegistry::getDataTypeClass('R4B', $modelName),
                'Primitive' => ModelRegistry::getPrimitiveClass('R4B', $modelName),
                'Enum'      => ModelRegistry::getEnumClass('R4B', $modelName),
                default     => throw new \InvalidArgumentException("Unknown model type: {$modelType}")
            };

            // Verify consistent namespace pattern
            $expectedPattern = "Ardenexal\\FHIRTools\\Component\\Models\\R4B\\{$modelType}\\FHIR{$modelName}";
            self::assertEquals($expectedPattern, $className);

            // Verify all classes follow the same base namespace structure
            self::assertStringStartsWith('Ardenexal\\FHIRTools\\Component\\Models\\R4B\\', $className);

            // Verify version detection is consistent
            self::assertEquals('R4B', VersionDetector::detectVersionFromClassName($className));

            // Verify model type detection is consistent
            self::assertEquals($modelType, VersionDetector::getModelType($className));

            // Verify Models component detection is consistent
            self::assertTrue(VersionDetector::isModelsComponentClass($className));
        });
    }
}
