<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\AllLanguagesType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Duration;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\EncounterStatusType;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Period;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\VirtualServiceDetail;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\DateTimePrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Encounter\EncounterAdmission;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Encounter\EncounterDiagnosis;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Encounter\EncounterLocation;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Encounter\EncounterParticipant;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\Encounter\EncounterReason;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Encounter
 *
 * @description An interaction between a patient and healthcare provider(s) for the purpose of providing healthcare service(s) or assessing the health status of a patient.  Encounter is primarily used to record information about the actual activities that occurred, where Appointment is used to record planned activities.
 */
#[FhirResource(type: 'Encounter', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Encounter', fhirVersion: 'R5')]
class EncounterResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar')]
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        #[FhirProperty(fhirType: 'Meta', propertyKind: 'complex')]
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        #[FhirProperty(fhirType: 'uri', propertyKind: 'primitive')]
        public ?UriPrimitive $implicitRules = null,
        /** @var AllLanguagesType|null language Language of the resource content */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?AllLanguagesType $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        #[FhirProperty(fhirType: 'Narrative', propertyKind: 'complex')]
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        #[FhirProperty(fhirType: 'Resource', propertyKind: 'resource', isArray: true)]
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var array<Identifier> identifier Identifier(s) by which this encounter is known */
        #[FhirProperty(
            fhirType: 'Identifier',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Identifier',
        )]
        public array $identifier = [],
        /** @var EncounterStatusType|null status planned | in-progress | on-hold | discharged | completed | cancelled | discontinued | entered-in-error | unknown */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?EncounterStatusType $status = null,
        /** @var array<CodeableConcept> class Classification of patient encounter context - e.g. Inpatient, outpatient */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $class = [],
        /** @var CodeableConcept|null priority Indicates the urgency of the encounter */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $priority = null,
        /** @var array<CodeableConcept> type Specific type of encounter (e.g. e-mail consultation, surgical day-care, ...) */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $type = [],
        /** @var array<CodeableReference> serviceType Specific type of service */
        #[FhirProperty(
            fhirType: 'CodeableReference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableReference',
        )]
        public array $serviceType = [],
        /** @var Reference|null subject The patient or group related to this encounter */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $subject = null,
        /** @var CodeableConcept|null subjectStatus The current status of the subject in relation to the Encounter */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $subjectStatus = null,
        /** @var array<Reference> episodeOfCare Episode(s) of care that this encounter should be recorded against */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $episodeOfCare = [],
        /** @var array<Reference> basedOn The request that initiated this encounter */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $basedOn = [],
        /** @var array<Reference> careTeam The group(s) that are allocated to participate in this encounter */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $careTeam = [],
        /** @var Reference|null partOf Another Encounter this encounter is part of */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $partOf = null,
        /** @var Reference|null serviceProvider The organization (facility) responsible for this encounter */
        #[FhirProperty(fhirType: 'Reference', propertyKind: 'complex')]
        public ?Reference $serviceProvider = null,
        /** @var array<EncounterParticipant> participant List of participants involved in the encounter */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Encounter\EncounterParticipant',
        )]
        public array $participant = [],
        /** @var array<Reference> appointment The appointment that scheduled this encounter */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $appointment = [],
        /** @var array<VirtualServiceDetail> virtualService Connection details of a virtual service (e.g. conference call) */
        #[FhirProperty(
            fhirType: 'VirtualServiceDetail',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\VirtualServiceDetail',
        )]
        public array $virtualService = [],
        /** @var Period|null actualPeriod The actual start and end time of the encounter */
        #[FhirProperty(fhirType: 'Period', propertyKind: 'complex')]
        public ?Period $actualPeriod = null,
        /** @var DateTimePrimitive|null plannedStartDate The planned start date/time (or admission date) of the encounter */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $plannedStartDate = null,
        /** @var DateTimePrimitive|null plannedEndDate The planned end date/time (or discharge date) of the encounter */
        #[FhirProperty(fhirType: 'dateTime', propertyKind: 'primitive')]
        public ?DateTimePrimitive $plannedEndDate = null,
        /** @var Duration|null length Actual quantity of time the encounter lasted (less time absent) */
        #[FhirProperty(fhirType: 'Duration', propertyKind: 'complex')]
        public ?Duration $length = null,
        /** @var array<EncounterReason> reason The list of medical reasons that are expected to be addressed during the episode of care */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Encounter\EncounterReason',
        )]
        public array $reason = [],
        /** @var array<EncounterDiagnosis> diagnosis The list of diagnosis relevant to this encounter */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Encounter\EncounterDiagnosis',
        )]
        public array $diagnosis = [],
        /** @var array<Reference> account The set of accounts that may be used for billing for this Encounter */
        #[FhirProperty(
            fhirType: 'Reference',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Reference',
        )]
        public array $account = [],
        /** @var array<CodeableConcept> dietPreference Diet preferences reported by the patient */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $dietPreference = [],
        /** @var array<CodeableConcept> specialArrangement Wheelchair, translator, stretcher, etc */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $specialArrangement = [],
        /** @var array<CodeableConcept> specialCourtesy Special courtesies (VIP, board member) */
        #[FhirProperty(
            fhirType: 'CodeableConcept',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept',
        )]
        public array $specialCourtesy = [],
        /** @var EncounterAdmission|null admission Details about the admission to a healthcare service */
        #[FhirProperty(fhirType: 'BackboneElement', propertyKind: 'backbone')]
        public ?EncounterAdmission $admission = null,
        /** @var array<EncounterLocation> location List of locations where the patient has been */
        #[FhirProperty(
            fhirType: 'BackboneElement',
            propertyKind: 'backbone',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\Encounter\EncounterLocation',
        )]
        public array $location = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
