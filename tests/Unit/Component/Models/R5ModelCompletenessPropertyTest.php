<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\Models;

use Ardenexal\FHIRTools\Component\Models\Utility\ModelRegistry;
use Ardenexal\FHIRTools\Component\Models\Utility\VersionDetector;
use Eris\Generator;
use Eris\TestTrait;
use PHPUnit\Framework\TestCase;

/**
 * Property-based test for R5 model completeness.
 *
 * **Feature: fhir-models-component, Property 1: Version-specific namespace isolation (R5)**
 *
 * Tests that R5 models are properly organized in version-specific namespaces
 * and maintain isolation from other FHIR versions.
 */
class R5ModelCompletenessPropertyTest extends TestCase
{
    use TestTrait;

    /**
     * Test that R5 resources are properly isolated in version-specific namespaces.
     *
     * **Feature: fhir-models-component, Property 1: Version-specific namespace isolation (R5)**
     * **Validates: Requirements 8.5**
     *
     * Property: For any R5 resource name, the generated class should be in the
     * Ardenexal\FHIRTools\Component\Models\R5\Resource namespace and isolated from other versions.
     */
    public function testR5ResourceNamespaceIsolation(): void
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
                'ActivityDefinition', 'AdverseEvent', 'AllergyIntolerance', 'AuditEvent',
                'BiologicallyDerivedProduct', 'BodyStructure', 'CapabilityStatement', 'CarePlan',
                'CareTeam', 'CatalogEntry', 'ChargeItem', 'ChargeItemDefinition', 'Citation',
                'ClaimResponse', 'ClinicalImpression', 'ClinicalUseDefinition', 'CodeSystem',
                'ConceptMap', 'DetectedIssue', 'DeviceDefinition', 'DeviceMetric',
                'DeviceRequest', 'DeviceUseStatement', 'DocumentManifest', 'DocumentReference',
                'ExampleScenario', 'ExplanationOfBenefit', 'FamilyMemberHistory', 'GraphDefinition',
                'GuidanceResponse', 'HealthcareService', 'ImagingStudy', 'ImmunizationEvaluation',
                'ImmunizationRecommendation', 'ImplementationGuide', 'InsurancePlan', 'MedicationAdministration',
                'MedicationDispense', 'MedicationKnowledge', 'MedicationStatement', 'MedicinalProductDefinition',
                'MessageDefinition', 'MessageHeader', 'MolecularSequence', 'NamingSystem',
                'NutritionOrder', 'NutritionProduct', 'ObservationDefinition', 'OperationDefinition',
                'OperationOutcome', 'Parameters', 'Person', 'PlanDefinition', 'PractitionerRole',
                'Questionnaire', 'QuestionnaireResponse', 'RegulatedAuthorization', 'RelatedPerson',
                'RequestGroup', 'ResearchDefinition', 'ResearchElementDefinition', 'ResearchStudy',
                'ResearchSubject', 'RiskAssessment', 'SearchParameter', 'ServiceRequest',
                'StructureDefinition', 'StructureMap', 'SupplyDelivery', 'SupplyRequest',
                'TestReport', 'TestScript', 'ValueSet', 'VerificationResult', 'VisionPrescription',
            ]),
        )->then(function(string $resourceName): void {
            $className = ModelRegistry::getResourceClass('R5', $resourceName);

            // Verify the class is in the correct R5 Resource namespace
            self::assertStringContainsString('\\Component\\Models\\R5\\Resource\\', $className);
            self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R5\\Resource\\FHIR' . $resourceName, $className);

            // Verify version detection works correctly
            $detectedVersion = VersionDetector::detectVersionFromClassName($className);
            self::assertEquals('R5', $detectedVersion);

            // Verify it's identified as a Models component class
            self::assertTrue(VersionDetector::isModelsComponentClass($className));

            // Verify model type detection
            $modelType = VersionDetector::getModelType($className);
            self::assertEquals('Resource', $modelType);

            // Verify version-specific class detection
            self::assertTrue(VersionDetector::isVersionSpecificClass($className, 'R5'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R4'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R4B'));

            // Verify isolation from other versions
            $r4ClassName  = ModelRegistry::getResourceClass('R4', $resourceName);
            $r4bClassName = ModelRegistry::getResourceClass('R4B', $resourceName);

            self::assertNotEquals($className, $r4ClassName);
            self::assertNotEquals($className, $r4bClassName);
            self::assertStringContainsString('\\R4\\Resource\\', $r4ClassName);
            self::assertStringContainsString('\\R4B\\Resource\\', $r4bClassName);
        });
    }

    /**
     * Test that R5 data types are properly isolated in version-specific namespaces.
     *
     * **Feature: fhir-models-component, Property 1: Version-specific namespace isolation (R5)**
     * **Validates: Requirements 8.5**
     *
     * Property: For any R5 data type name, the generated class should be in the
     * Ardenexal\FHIRTools\Component\Models\R5\DataType namespace and isolated from other versions.
     */
    public function testR5DataTypeNamespaceIsolation(): void
    {
        $this->forAll(
            Generator\elements([
                'HumanName', 'Address', 'ContactPoint', 'Identifier', 'Coding',
                'CodeableConcept', 'Quantity', 'Range', 'Ratio', 'Period',
                'Attachment', 'Annotation', 'Signature', 'Reference', 'Meta',
                'Narrative', 'Extension', 'Age', 'Count', 'Distance', 'Duration',
                'Money', 'Timing', 'Dosage', 'Expression', 'Contributor',
                'DataRequirement', 'ParameterDefinition', 'RelatedArtifact', 'TriggerDefinition',
                'UsageContext', 'ContactDetail', 'ElementDefinition', 'MarketingStatus',
                'Population', 'ProductShelfLife', 'ProdCharacteristic', 'SubstanceAmount',
                'CodeableReference', 'Availability', 'ExtendedContactDetail', 'MonetaryComponent',
                'VirtualServiceDetail', 'RatioRange',
            ]),
        )->then(function(string $dataTypeName): void {
            $className = ModelRegistry::getDataTypeClass('R5', $dataTypeName);

            // Verify the class is in the correct R5 DataType namespace
            self::assertStringContainsString('\\Component\\Models\\R5\\DataType\\', $className);
            self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R5\\DataType\\FHIR' . $dataTypeName, $className);

            // Verify version detection works correctly
            $detectedVersion = VersionDetector::detectVersionFromClassName($className);
            self::assertEquals('R5', $detectedVersion);

            // Verify it's identified as a Models component class
            self::assertTrue(VersionDetector::isModelsComponentClass($className));

            // Verify model type detection
            $modelType = VersionDetector::getModelType($className);
            self::assertEquals('DataType', $modelType);

            // Verify version-specific class detection
            self::assertTrue(VersionDetector::isVersionSpecificClass($className, 'R5'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R4'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R4B'));

            // Verify isolation from other versions
            $r4ClassName  = ModelRegistry::getDataTypeClass('R4', $dataTypeName);
            $r4bClassName = ModelRegistry::getDataTypeClass('R4B', $dataTypeName);

            self::assertNotEquals($className, $r4ClassName);
            self::assertNotEquals($className, $r4bClassName);
            self::assertStringContainsString('\\R4\\DataType\\', $r4ClassName);
            self::assertStringContainsString('\\R4B\\DataType\\', $r4bClassName);
        });
    }

    /**
     * Test that R5 primitive types are properly isolated in version-specific namespaces.
     *
     * **Feature: fhir-models-component, Property 1: Version-specific namespace isolation (R5)**
     * **Validates: Requirements 8.5**
     *
     * Property: For any R5 primitive type name, the generated class should be in the
     * Ardenexal\FHIRTools\Component\Models\R5\Primitive namespace and isolated from other versions.
     */
    public function testR5PrimitiveTypeNamespaceIsolation(): void
    {
        $this->forAll(
            Generator\elements([
                'String', 'Integer', 'Boolean', 'Decimal', 'DateTime', 'Date',
                'Time', 'Instant', 'Uri', 'Url', 'Canonical', 'Base64Binary',
                'Code', 'Id', 'Markdown', 'Oid', 'PositiveInt', 'UnsignedInt',
                'Uuid', 'Xhtml', 'Integer64',
            ]),
        )->then(function(string $primitiveName): void {
            $className = ModelRegistry::getPrimitiveClass('R5', $primitiveName);

            // Verify the class is in the correct R5 Primitive namespace
            self::assertStringContainsString('\\Component\\Models\\R5\\Primitive\\', $className);
            self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R5\\Primitive\\FHIR' . $primitiveName, $className);

            // Verify version detection works correctly
            $detectedVersion = VersionDetector::detectVersionFromClassName($className);
            self::assertEquals('R5', $detectedVersion);

            // Verify it's identified as a Models component class
            self::assertTrue(VersionDetector::isModelsComponentClass($className));

            // Verify model type detection
            $modelType = VersionDetector::getModelType($className);
            self::assertEquals('Primitive', $modelType);

            // Verify version-specific class detection
            self::assertTrue(VersionDetector::isVersionSpecificClass($className, 'R5'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R4'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R4B'));

            // Verify isolation from other versions
            $r4ClassName  = ModelRegistry::getPrimitiveClass('R4', $primitiveName);
            $r4bClassName = ModelRegistry::getPrimitiveClass('R4B', $primitiveName);

            self::assertNotEquals($className, $r4ClassName);
            self::assertNotEquals($className, $r4bClassName);
            self::assertStringContainsString('\\R4\\Primitive\\', $r4ClassName);
            self::assertStringContainsString('\\R4B\\Primitive\\', $r4bClassName);
        });
    }

    /**
     * Test that R5 enums are properly isolated in version-specific namespaces.
     *
     * **Feature: fhir-models-component, Property 1: Version-specific namespace isolation (R5)**
     * **Validates: Requirements 8.5**
     *
     * Property: For any R5 enum name, the generated class should be in the
     * Ardenexal\FHIRTools\Component\Models\R5\Enum namespace and isolated from other versions.
     */
    public function testR5EnumNamespaceIsolation(): void
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
                'PublicationStatus', 'FHIRVersion', 'SearchParamType', 'ResourceType',
                'CapabilityStatementKind', 'RestfulCapabilityMode', 'TypeRestfulInteraction',
                'SystemRestfulInteraction', 'EventCapabilityMode', 'MessageSignificanceCategory',
                'ConditionalDeleteStatus', 'ConditionalReadStatus', 'ReferenceHandlingPolicy',
                'SearchModifierCode', 'SearchComparator', 'DocumentMode', 'SortDirection',
                'VersionIndependentResourceTypesAll', 'FHIRTypes', 'DataAbsentReason', 'ProvenanceEntityRole',
                'AuditEventAction', 'AuditEventOutcome', 'NetworkType', 'AuditEventAgentNetworkType',
                'ConsentState', 'ConsentProvisionType', 'ConsentDataMeaning', 'ContractResourceStatusCodes',
                'ContractResourcePublicationStatusCodes', 'ContractResourcePolicyRuleCodes', 'ListMode',
                'ListStatus', 'EpisodeOfCareStatus', 'EncounterLocationStatus', 'AccountStatus',
                'InvoiceStatus', 'InvoicePriceComponentType', 'SlotStatus', 'DeviceNameType',
                'DeviceStatus', 'FlagStatus', 'AllergyIntoleranceType', 'AllergyIntoleranceSeverity',
                'ReactionEventSeverity', 'ConditionCategoryCodes', 'ClinicalImpressionStatus',
                'DetectedIssueSeverity', 'ObservationRangeCategory', 'DiagnosticReportStatus',
                'SpecimenStatus', 'SpecimenContainedPreference', 'RequestStatus', 'RequestIntent',
                'RequestPriority', 'MedicationKnowledgeStatusCodes', 'MedicationStatusCodes',
                'MedicationAdministrationStatusCodes', 'MedicationDispenseStatusCodes', 'MedicationStatementStatusCodes',
                'ImmunizationStatusCodes', 'ImmunizationEvaluationStatusCodes', 'ImmunizationRecommendationStatusCodes',
                'CareTeamStatus', 'GoalLifecycleStatus', 'GoalAchievementStatus', 'CarePlanStatus',
                'CarePlanIntent', 'CarePlanActivityStatus', 'CarePlanActivityKind', 'RequestResourceTypes',
                'ActionRequiredBehavior', 'ActionPrecheckBehavior', 'ActionCardinalityBehavior', 'ActionSelectionBehavior',
                'ActionGroupingBehavior', 'ActionRelationshipType', 'ActionParticipantType', 'PlanDefinitionType',
                'ActivityDefinitionKind', 'ResourceTypeLink', 'ParticipantResourceTypes', 'ActionConditionKind',
                'TriggerType', 'UsageContextType', 'RelatedArtifactType', 'CitationStatusType',
            ]),
        )->then(function(string $enumName): void {
            $className = ModelRegistry::getEnumClass('R5', $enumName);

            // Verify the class is in the correct R5 Enum namespace
            self::assertStringContainsString('\\Component\\Models\\R5\\Enum\\', $className);
            self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R5\\Enum\\FHIR' . $enumName, $className);

            // Verify version detection works correctly
            $detectedVersion = VersionDetector::detectVersionFromClassName($className);
            self::assertEquals('R5', $detectedVersion);

            // Verify it's identified as a Models component class
            self::assertTrue(VersionDetector::isModelsComponentClass($className));

            // Verify model type detection
            $modelType = VersionDetector::getModelType($className);
            self::assertEquals('Enum', $modelType);

            // Verify version-specific class detection
            self::assertTrue(VersionDetector::isVersionSpecificClass($className, 'R5'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R4'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R4B'));

            // Verify isolation from other versions
            $r4ClassName  = ModelRegistry::getEnumClass('R4', $enumName);
            $r4bClassName = ModelRegistry::getEnumClass('R4B', $enumName);

            self::assertNotEquals($className, $r4ClassName);
            self::assertNotEquals($className, $r4bClassName);
            self::assertStringContainsString('\\R4\\Enum\\', $r4ClassName);
            self::assertStringContainsString('\\R4B\\Enum\\', $r4bClassName);
        });
    }

    /**
     * Test that R5 backbone elements are properly isolated in version-specific namespaces.
     *
     * **Feature: fhir-models-component, Property 1: Version-specific namespace isolation (R5)**
     * **Validates: Requirements 8.5**
     *
     * Property: For any R5 backbone element, the generated class should be in the
     * appropriate resource subdirectory within the R5 namespace and isolated from other versions.
     */
    public function testR5BackboneElementNamespaceIsolation(): void
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
                ['Questionnaire', 'Item'],
                ['QuestionnaireResponse', 'Item'],
                ['CapabilityStatement', 'Software'],
                ['CapabilityStatement', 'Implementation'],
                ['CapabilityStatement', 'Rest'],
                ['CapabilityStatement', 'Messaging'],
                ['CapabilityStatement', 'Document'],
                ['StructureDefinition', 'Mapping'],
                ['StructureDefinition', 'Context'],
                ['StructureDefinition', 'Snapshot'],
                ['StructureDefinition', 'Differential'],
                ['ValueSet', 'Compose'],
                ['ValueSet', 'Expansion'],
                ['CodeSystem', 'Concept'],
                ['CodeSystem', 'Filter'],
                ['CodeSystem', 'Property'],
            ]),
        )->then(function(array $backboneInfo): void {
            [$resourceName, $elementName] = $backboneInfo;
            $className                    = ModelRegistry::getBackboneElementClass('R5', $resourceName, $elementName);

            // Verify the class is in the correct R5 Resource subdirectory
            self::assertStringContainsString("\\Component\\Models\\R5\\Resource\\{$resourceName}\\", $className);
            self::assertEquals("Ardenexal\\FHIRTools\\Component\\Models\\R5\\Resource\\{$resourceName}\\FHIR{$resourceName}{$elementName}", $className);

            // Verify version detection works correctly
            $detectedVersion = VersionDetector::detectVersionFromClassName($className);
            self::assertEquals('R5', $detectedVersion);

            // Verify it's identified as a Models component class
            self::assertTrue(VersionDetector::isModelsComponentClass($className));

            // Verify version-specific class detection
            self::assertTrue(VersionDetector::isVersionSpecificClass($className, 'R5'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R4'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R4B'));

            // Verify isolation from other versions
            $r4ClassName  = ModelRegistry::getBackboneElementClass('R4', $resourceName, $elementName);
            $r4bClassName = ModelRegistry::getBackboneElementClass('R4B', $resourceName, $elementName);

            self::assertNotEquals($className, $r4ClassName);
            self::assertNotEquals($className, $r4bClassName);
            self::assertStringContainsString("\\R4\\Resource\\{$resourceName}\\", $r4ClassName);
            self::assertStringContainsString("\\R4B\\Resource\\{$resourceName}\\", $r4bClassName);
        });
    }

    /**
     * Test that R5 code type wrappers are properly isolated in version-specific namespaces.
     *
     * **Feature: fhir-models-component, Property 1: Version-specific namespace isolation (R5)**
     * **Validates: Requirements 8.5**
     *
     * Property: For any R5 enum with a code type wrapper, the generated class should be in the
     * Ardenexal\FHIRTools\Component\Models\R5\DataType namespace and isolated from other versions.
     */
    public function testR5CodeTypeNamespaceIsolation(): void
    {
        $this->forAll(
            Generator\elements([
                'AdministrativeGender', 'ObservationStatus', 'ContactPointSystem',
                'ContactPointUse', 'AddressType', 'AddressUse', 'NameUse',
                'IdentifierUse', 'AppointmentStatus', 'ParticipationStatus',
                'EncounterStatus', 'LocationStatus', 'BundleType', 'CompositionStatus',
                'PublicationStatus', 'FHIRVersion', 'SearchParamType', 'ResourceType',
                'CapabilityStatementKind', 'RestfulCapabilityMode', 'TypeRestfulInteraction',
                'SystemRestfulInteraction', 'EventCapabilityMode', 'MessageSignificanceCategory',
            ]),
        )->then(function(string $enumName): void {
            $className = ModelRegistry::getCodeTypeClass('R5', $enumName);

            // Verify the class is in the correct R5 DataType namespace (code types are data types)
            self::assertStringContainsString('\\Component\\Models\\R5\\DataType\\', $className);
            self::assertEquals('Ardenexal\\FHIRTools\\Component\\Models\\R5\\DataType\\FHIR' . $enumName . 'Type', $className);

            // Verify version detection works correctly
            $detectedVersion = VersionDetector::detectVersionFromClassName($className);
            self::assertEquals('R5', $detectedVersion);

            // Verify it's identified as a Models component class
            self::assertTrue(VersionDetector::isModelsComponentClass($className));

            // Verify model type detection (code types are in DataType namespace)
            $modelType = VersionDetector::getModelType($className);
            self::assertEquals('DataType', $modelType);

            // Verify version-specific class detection
            self::assertTrue(VersionDetector::isVersionSpecificClass($className, 'R5'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R4'));
            self::assertFalse(VersionDetector::isVersionSpecificClass($className, 'R4B'));

            // Verify isolation from other versions
            $r4ClassName  = ModelRegistry::getCodeTypeClass('R4', $enumName);
            $r4bClassName = ModelRegistry::getCodeTypeClass('R4B', $enumName);

            self::assertNotEquals($className, $r4ClassName);
            self::assertNotEquals($className, $r4bClassName);
            self::assertStringContainsString('\\R4\\DataType\\', $r4ClassName);
            self::assertStringContainsString('\\R4B\\DataType\\', $r4bClassName);
        });
    }

    /**
     * Test that R5 namespace organization is consistent across all model types.
     *
     * **Feature: fhir-models-component, Property 1: Version-specific namespace isolation (R5)**
     * **Validates: Requirements 8.5**
     *
     * Property: For any R5 model type and name combination, the namespace organization
     * should be consistent and follow the established pattern.
     */
    public function testR5NamespaceOrganizationConsistency(): void
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
                'Resource'  => ModelRegistry::getResourceClass('R5', $modelName),
                'DataType'  => ModelRegistry::getDataTypeClass('R5', $modelName),
                'Primitive' => ModelRegistry::getPrimitiveClass('R5', $modelName),
                'Enum'      => ModelRegistry::getEnumClass('R5', $modelName),
                default     => throw new \InvalidArgumentException("Unknown model type: {$modelType}")
            };

            // Verify consistent namespace pattern
            $expectedPattern = "Ardenexal\\FHIRTools\\Component\\Models\\R5\\{$modelType}\\FHIR{$modelName}";
            self::assertEquals($expectedPattern, $className);

            // Verify all classes follow the same base namespace structure
            self::assertStringStartsWith('Ardenexal\\FHIRTools\\Component\\Models\\R5\\', $className);

            // Verify version detection is consistent
            self::assertEquals('R5', VersionDetector::detectVersionFromClassName($className));

            // Verify model type detection is consistent
            self::assertEquals($modelType, VersionDetector::getModelType($className));

            // Verify Models component detection is consistent
            self::assertTrue(VersionDetector::isModelsComponentClass($className));
        });
    }
}
