<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\Models;

use Ardenexal\FHIRTools\Component\Models\Utility\ModelRegistry;
use Ardenexal\FHIRTools\Component\Models\Utility\VersionDetector;
use Eris\Generator;
use Eris\TestTrait;
use PHPUnit\Framework\TestCase;

/**
 * Property-based test for R4 model completeness.
 *
 * **Feature: fhir-models-component, Property 1: Version-specific namespace isolation (R4)**
 *
 * Tests that R4 models are properly organized in version-specific namespaces
 * and maintain isolation from other FHIR versions.
 */
class R4ModelCompletenessPropertyTest extends TestCase
{
    use TestTrait;

    /**
     * Test that R4 resources are properly isolated in version-specific namespaces.
     *
     * **Feature: fhir-models-component, Property 1: Version-specific namespace isolation (R4)**
     * **Validates: Requirements 8.1, 8.2, 8.3**
     *
     * Property: For any R4 resource name, the generated class should be in the
     * Ardenexal\FHIRTools\Component\Models\R4\Resource namespace and isolated from other versions.
     */
    public function testR4ResourceNamespaceIsolation(): void
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
            $className = ModelRegistry::getResourceClass('R4', $resourceName);

            // Verify the class is in the correct R4 Resource namespace
            self::assertStringContainsString('\\Component\\Models\\R4\\Resource\\', $className);
            self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource\\FHIR' . $resourceName, $className);

            // Verify version detection works correctly
            $detectedVersion = VersionDetector::detectVersionFromClassName($className);
            self::assertEquals('R4', $detectedVersion);

            // Verify it's identified as a Models component class
            self::assertTrue(VersionDetector::isModelsComponentClass($className));

            // Verify model type detection
            $modelType = VersionDetector::getModelType($className);
            self::assertEquals('Resource', $modelType);

            // Verify version-specific class detection
            self::assertTrue(VersionDetector::isVersionSpecificClass($className, 'R4'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R4B'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R5'));

            // Verify isolation from other versions
            $r4bClassName = ModelRegistry::getResourceClass('R4B', $resourceName);
            $r5ClassName  = ModelRegistry::getResourceClass('R5', $resourceName);

            self::assertNotEquals($className, $r4bClassName);
            self::assertNotEquals($className, $r5ClassName);
            self::assertStringContainsString('\\R4B\\Resource\\', $r4bClassName);
            self::assertStringContainsString('\\R5\\Resource\\', $r5ClassName);
        });
    }

    /**
     * Test that R4 data types are properly isolated in version-specific namespaces.
     *
     * **Feature: fhir-models-component, Property 1: Version-specific namespace isolation (R4)**
     * **Validates: Requirements 8.1, 8.2, 8.3**
     *
     * Property: For any R4 data type name, the generated class should be in the
     * Ardenexal\FHIRTools\Component\Models\R4\DataType namespace and isolated from other versions.
     */
    public function testR4DataTypeNamespaceIsolation(): void
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
            $className = ModelRegistry::getDataTypeClass('R4', $dataTypeName);

            // Verify the class is in the correct R4 DataType namespace
            self::assertStringContainsString('\\Component\\Models\\R4\\DataType\\', $className);
            self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R4\\DataType\\FHIR' . $dataTypeName, $className);

            // Verify version detection works correctly
            $detectedVersion = VersionDetector::detectVersionFromClassName($className);
            self::assertEquals('R4', $detectedVersion);

            // Verify it's identified as a Models component class
            self::assertTrue(VersionDetector::isModelsComponentClass($className));

            // Verify model type detection
            $modelType = VersionDetector::getModelType($className);
            self::assertEquals('DataType', $modelType);

            // Verify version-specific class detection
            self::assertTrue(VersionDetector::isVersionSpecificClass($className, 'R4'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R4B'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R5'));

            // Verify isolation from other versions
            $r4bClassName = ModelRegistry::getDataTypeClass('R4B', $dataTypeName);
            $r5ClassName  = ModelRegistry::getDataTypeClass('R5', $dataTypeName);

            self::assertNotEquals($className, $r4bClassName);
            self::assertNotEquals($className, $r5ClassName);
            self::assertStringContainsString('\\R4B\\DataType\\', $r4bClassName);
            self::assertStringContainsString('\\R5\\DataType\\', $r5ClassName);
        });
    }

    /**
     * Test that R4 primitive types are properly isolated in version-specific namespaces.
     *
     * **Feature: fhir-models-component, Property 1: Version-specific namespace isolation (R4)**
     * **Validates: Requirements 8.1, 8.2, 8.3**
     *
     * Property: For any R4 primitive type name, the generated class should be in the
     * Ardenexal\FHIRTools\Component\Models\R4\Primitive namespace and isolated from other versions.
     */
    public function testR4PrimitiveTypeNamespaceIsolation(): void
    {
        $this->forAll(
            Generator\elements([
                'String', 'Integer', 'Boolean', 'Decimal', 'DateTime', 'Date',
                'Time', 'Instant', 'Uri', 'Url', 'Canonical', 'Base64Binary',
                'Code', 'Id', 'Markdown', 'Oid', 'PositiveInt', 'UnsignedInt',
                'Uuid', 'Xhtml',
            ]),
        )->then(function(string $primitiveName): void {
            $className = ModelRegistry::getPrimitiveClass('R4', $primitiveName);

            // Verify the class is in the correct R4 Primitive namespace
            self::assertStringContainsString('\\Component\\Models\\R4\\Primitive\\', $className);
            self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R4\\Primitive\\FHIR' . $primitiveName, $className);

            // Verify version detection works correctly
            $detectedVersion = VersionDetector::detectVersionFromClassName($className);
            self::assertEquals('R4', $detectedVersion);

            // Verify it's identified as a Models component class
            self::assertTrue(VersionDetector::isModelsComponentClass($className));

            // Verify model type detection
            $modelType = VersionDetector::getModelType($className);
            self::assertEquals('Primitive', $modelType);

            // Verify version-specific class detection
            self::assertTrue(VersionDetector::isVersionSpecificClass($className, 'R4'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R4B'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R5'));

            // Verify isolation from other versions
            $r4bClassName = ModelRegistry::getPrimitiveClass('R4B', $primitiveName);
            $r5ClassName  = ModelRegistry::getPrimitiveClass('R5', $primitiveName);

            self::assertNotEquals($className, $r4bClassName);
            self::assertNotEquals($className, $r5ClassName);
            self::assertStringContainsString('\\R4B\\Primitive\\', $r4bClassName);
            self::assertStringContainsString('\\R5\\Primitive\\', $r5ClassName);
        });
    }

    /**
     * Test that R4 enums are properly isolated in version-specific namespaces.
     *
     * **Feature: fhir-models-component, Property 1: Version-specific namespace isolation (R4)**
     * **Validates: Requirements 8.1, 8.2, 8.3**
     *
     * Property: For any R4 enum name, the generated class should be in the
     * Ardenexal\FHIRTools\Component\Models\R4\Enum namespace and isolated from other versions.
     */
    public function testR4EnumNamespaceIsolation(): void
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
            $className = ModelRegistry::getEnumClass('R4', $enumName);

            // Verify the class is in the correct R4 Enum namespace
            self::assertStringContainsString('\\Component\\Models\\R4\\Enum\\', $className);
            self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R4\\Enum\\FHIR' . $enumName, $className);

            // Verify version detection works correctly
            $detectedVersion = VersionDetector::detectVersionFromClassName($className);
            self::assertEquals('R4', $detectedVersion);

            // Verify it's identified as a Models component class
            self::assertTrue(VersionDetector::isModelsComponentClass($className));

            // Verify model type detection
            $modelType = VersionDetector::getModelType($className);
            self::assertEquals('Enum', $modelType);

            // Verify version-specific class detection
            self::assertTrue(VersionDetector::isVersionSpecificClass($className, 'R4'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R4B'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R5'));

            // Verify isolation from other versions
            $r4bClassName = ModelRegistry::getEnumClass('R4B', $enumName);
            $r5ClassName  = ModelRegistry::getEnumClass('R5', $enumName);

            self::assertNotEquals($className, $r4bClassName);
            self::assertNotEquals($className, $r5ClassName);
            self::assertStringContainsString('\\R4B\\Enum\\', $r4bClassName);
            self::assertStringContainsString('\\R5\\Enum\\', $r5ClassName);
        });
    }

    /**
     * Test that R4 backbone elements are properly isolated in version-specific namespaces.
     *
     * **Feature: fhir-models-component, Property 1: Version-specific namespace isolation (R4)**
     * **Validates: Requirements 8.1, 8.2, 8.3**
     *
     * Property: For any R4 backbone element, the generated class should be in the
     * appropriate resource subdirectory within the R4 namespace and isolated from other versions.
     */
    public function testR4BackboneElementNamespaceIsolation(): void
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
            $className                    = ModelRegistry::getBackboneElementClass('R4', $resourceName, $elementName);

            // Verify the class is in the correct R4 Resource subdirectory
            self::assertStringContainsString("\\Component\\Models\\R4\\Resource\\{$resourceName}\\", $className);
            self::assertEquals("Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource\\{$resourceName}\\FHIR{$resourceName}{$elementName}", $className);

            // Verify version detection works correctly
            $detectedVersion = VersionDetector::detectVersionFromClassName($className);
            self::assertEquals('R4', $detectedVersion);

            // Verify it's identified as a Models component class
            self::assertTrue(VersionDetector::isModelsComponentClass($className));

            // Verify version-specific class detection
            self::assertTrue(VersionDetector::isVersionSpecificClass($className, 'R4'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R4B'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R5'));

            // Verify isolation from other versions
            $r4bClassName = ModelRegistry::getBackboneElementClass('R4B', $resourceName, $elementName);
            $r5ClassName  = ModelRegistry::getBackboneElementClass('R5', $resourceName, $elementName);

            self::assertNotEquals($className, $r4bClassName);
            self::assertNotEquals($className, $r5ClassName);
            self::assertStringContainsString("\\R4B\\Resource\\{$resourceName}\\", $r4bClassName);
            self::assertStringContainsString("\\R5\\Resource\\{$resourceName}\\", $r5ClassName);
        });
    }

    /**
     * Test that R4 code type wrappers are properly isolated in version-specific namespaces.
     *
     * **Feature: fhir-models-component, Property 1: Version-specific namespace isolation (R4)**
     * **Validates: Requirements 8.1, 8.2, 8.3**
     *
     * Property: For any R4 enum with a code type wrapper, the generated class should be in the
     * Ardenexal\FHIRTools\Component\Models\R4\DataType namespace and isolated from other versions.
     */
    public function testR4CodeTypeNamespaceIsolation(): void
    {
        $this->forAll(
            Generator\elements([
                'AdministrativeGender', 'ObservationStatus', 'ContactPointSystem',
                'ContactPointUse', 'AddressType', 'AddressUse', 'NameUse',
                'IdentifierUse', 'AppointmentStatus', 'ParticipationStatus',
                'EncounterStatus', 'LocationStatus', 'BundleType', 'CompositionStatus',
            ]),
        )->then(function(string $enumName): void {
            $className = ModelRegistry::getCodeTypeClass('R4', $enumName);

            // Verify the class is in the correct R4 DataType namespace (code types are data types)
            self::assertStringContainsString('\\Component\\Models\\R4\\DataType\\', $className);
            self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R4\\DataType\\FHIR' . $enumName . 'Type', $className);

            // Verify version detection works correctly
            $detectedVersion = VersionDetector::detectVersionFromClassName($className);
            self::assertEquals('R4', $detectedVersion);

            // Verify it's identified as a Models component class
            self::assertTrue(VersionDetector::isModelsComponentClass($className));

            // Verify model type detection (code types are in DataType namespace)
            $modelType = VersionDetector::getModelType($className);
            self::assertEquals('DataType', $modelType);

            // Verify version-specific class detection
            self::assertTrue(VersionDetector::isVersionSpecificClass($className, 'R4'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R4B'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R5'));

            // Verify isolation from other versions
            $r4bClassName = ModelRegistry::getCodeTypeClass('R4B', $enumName);
            $r5ClassName  = ModelRegistry::getCodeTypeClass('R5', $enumName);

            self::assertNotEquals($className, $r4bClassName);
            self::assertNotEquals($className, $r5ClassName);
            self::assertStringContainsString('\\R4B\\DataType\\', $r4bClassName);
            self::assertStringContainsString('\\R5\\DataType\\', $r5ClassName);
        });
    }

    /**
     * Test that R4 namespace organization is consistent across all model types.
     *
     * **Feature: fhir-models-component, Property 1: Version-specific namespace isolation (R4)**
     * **Validates: Requirements 8.1, 8.2, 8.3**
     *
     * Property: For any R4 model type and name combination, the namespace organization
     * should be consistent and follow the established pattern.
     */
    public function testR4NamespaceOrganizationConsistency(): void
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
                'Resource'  => ModelRegistry::getResourceClass('R4', $modelName),
                'DataType'  => ModelRegistry::getDataTypeClass('R4', $modelName),
                'Primitive' => ModelRegistry::getPrimitiveClass('R4', $modelName),
                'Enum'      => ModelRegistry::getEnumClass('R4', $modelName),
                default     => throw new \InvalidArgumentException("Unknown model type: {$modelType}")
            };

            // Verify consistent namespace pattern
            $expectedPattern = "Ardenexal\\FHIRTools\\Component\\Models\\R4\\{$modelType}\\FHIR{$modelName}";
            self::assertEquals($expectedPattern, $className);

            // Verify all classes follow the same base namespace structure
            self::assertStringStartsWith('Ardenexal\\FHIRTools\\Component\\Models\\R4\\', $className);

            // Verify version detection is consistent
            self::assertEquals('R4', VersionDetector::detectVersionFromClassName($className));

            // Verify model type detection is consistent
            self::assertEquals($modelType, VersionDetector::getModelType($className));

            // Verify Models component detection is consistent
            self::assertTrue(VersionDetector::isModelsComponentClass($className));
        });
    }
}
