<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Extension;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRExtensionContext;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;

/**
 * @author HL7 International / FHIR Infrastructure
 *
 * @see http://hl7.org/fhir/StructureDefinition/workflow-episodeOfCare
 *
 * @description Identifies the episode(s) of care that this resource is relevant to.  Establishes the EpisodeOfCare as a 'grouper' of resources that are relevant to that episode.
 */
#[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/workflow-episodeOfCare', fhirVersion: 'R5')]
#[FHIRExtensionContext(type: 'element', expression: 'AdverseEvent')]
#[FHIRExtensionContext(type: 'element', expression: 'Appointment')]
#[FHIRExtensionContext(type: 'element', expression: 'Basic')]
#[FHIRExtensionContext(type: 'element', expression: 'CarePlan')]
#[FHIRExtensionContext(type: 'element', expression: 'ClinicalImpression')]
#[FHIRExtensionContext(type: 'element', expression: 'Communication')]
#[FHIRExtensionContext(type: 'element', expression: 'CommunicationRequest')]
#[FHIRExtensionContext(type: 'element', expression: 'Composition')]
#[FHIRExtensionContext(type: 'element', expression: 'Condition')]
#[FHIRExtensionContext(type: 'element', expression: 'Consent')]
#[FHIRExtensionContext(type: 'element', expression: 'DetectedIssue')]
#[FHIRExtensionContext(type: 'element', expression: 'DeviceRequest')]
#[FHIRExtensionContext(type: 'element', expression: 'DeviceUsage')]
#[FHIRExtensionContext(type: 'element', expression: 'DeviceUseStatement')]
#[FHIRExtensionContext(type: 'element', expression: 'DiagnosticReport')]
#[FHIRExtensionContext(type: 'element', expression: 'DocumentReference')]
#[FHIRExtensionContext(type: 'element', expression: 'EnrollmentRequest')]
#[FHIRExtensionContext(type: 'element', expression: 'EnrollmentResponse')]
#[FHIRExtensionContext(type: 'element', expression: 'FamilyMemberHistory')]
#[FHIRExtensionContext(type: 'element', expression: 'Flag')]
#[FHIRExtensionContext(type: 'element', expression: 'Goal')]
#[FHIRExtensionContext(type: 'element', expression: 'ImagingStudy')]
#[FHIRExtensionContext(type: 'element', expression: 'Immunization')]
#[FHIRExtensionContext(type: 'element', expression: 'ImmunizationEvaluation')]
#[FHIRExtensionContext(type: 'element', expression: 'ImmunizationRecommendation')]
#[FHIRExtensionContext(type: 'element', expression: 'Invoice')]
#[FHIRExtensionContext(type: 'element', expression: 'List')]
#[FHIRExtensionContext(type: 'element', expression: 'MeasureReport')]
#[FHIRExtensionContext(type: 'element', expression: 'MedicationAdministration')]
#[FHIRExtensionContext(type: 'element', expression: 'MedicationDispense')]
#[FHIRExtensionContext(type: 'element', expression: 'MedicationRequest')]
#[FHIRExtensionContext(type: 'element', expression: 'MedicationStatement')]
#[FHIRExtensionContext(type: 'element', expression: 'MolecularSequence')]
#[FHIRExtensionContext(type: 'element', expression: 'NutritionIntake')]
#[FHIRExtensionContext(type: 'element', expression: 'NutritionOrder')]
#[FHIRExtensionContext(type: 'element', expression: 'Observation')]
#[FHIRExtensionContext(type: 'element', expression: 'Procedure')]
#[FHIRExtensionContext(type: 'element', expression: 'QuestionnaireResponse')]
#[FHIRExtensionContext(type: 'element', expression: 'RequestOrchestration')]
#[FHIRExtensionContext(type: 'element', expression: 'RequestGroup')]
#[FHIRExtensionContext(type: 'element', expression: 'ResearchSubject')]
#[FHIRExtensionContext(type: 'element', expression: 'RiskAssessment')]
#[FHIRExtensionContext(type: 'element', expression: 'SupplyDelivery')]
#[FHIRExtensionContext(type: 'element', expression: 'SupplyRequest')]
#[FHIRExtensionContext(type: 'element', expression: 'VisionPrescription')]
#[FHIRExtensionContext(type: 'element', expression: 'ServiceRequest')]
#[FHIRExtensionContext(type: 'element', expression: 'ChargeItem')]
class EpisodeOfCareExtension extends Extension
{
    public function __construct(
        /** @var Reference|null valueReference Value of extension */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $valueReference = null,
        ?string $id = null,
        array $extension = [],
    ) {
        parent::__construct(
            id: $id,
            extension: $extension,
            url: 'http://hl7.org/fhir/StructureDefinition/workflow-episodeOfCare',
            value: $this->valueReference,
        );
    }
}
