<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\CarePlan;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRTargetProfile;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;

/**
 * @description Identifies a planned action to occur as part of the plan.  For example, a medication to be used, lab tests to perform, self-monitoring, education, etc.
 */
#[FHIRBackboneElement(parentResource: 'CarePlan', elementPath: 'CarePlan.activity', fhirVersion: 'R4')]
#[FHIRPathInvariant(
    key: 'cpl-3',
    severity: 'error',
    expression: 'detail.empty() or reference.empty()',
    human: 'Provide a reference or detail, not both',
)]
class CarePlanActivity extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true), FHIRIsModifier(reason: 'Modifier extensions are expected to modify the meaning or interpretation of the element that contains them')]
        public array $modifierExtension = [],
        /** @var array<CodeableConcept> outcomeCodeableConcept Results of the activity */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept',
        )]
        public array $outcomeCodeableConcept = [],
        /** @var array<Reference> outcomeReference Appointment, Encounter, Procedure, etc. */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference',
        )]
        #[FHIRTargetProfile(targetProfiles: ['http://hl7.org/fhir/StructureDefinition/Resource'])]
        public array $outcomeReference = [],
        /** @var array<Annotation> progress Comments about the activity status/progress */
        #[FhirProperty(
            fhirType: 'Annotation',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R4\DataType\Annotation',
        )]
        public array $progress = [],
        /** @var Reference|null reference Activity details defined in specific resource */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        #[FHIRTargetProfile(targetProfiles: [
            'http://hl7.org/fhir/StructureDefinition/Appointment',
            'http://hl7.org/fhir/StructureDefinition/CommunicationRequest',
            'http://hl7.org/fhir/StructureDefinition/DeviceRequest',
            'http://hl7.org/fhir/StructureDefinition/MedicationRequest',
            'http://hl7.org/fhir/StructureDefinition/NutritionOrder',
            'http://hl7.org/fhir/StructureDefinition/Task',
            'http://hl7.org/fhir/StructureDefinition/ServiceRequest',
            'http://hl7.org/fhir/StructureDefinition/VisionPrescription',
            'http://hl7.org/fhir/StructureDefinition/RequestGroup',
        ])]
        public ?Reference $reference = null,
        /** @var CarePlanActivityDetail|null detail In-line definition of activity */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?CarePlanActivityDetail $detail = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
