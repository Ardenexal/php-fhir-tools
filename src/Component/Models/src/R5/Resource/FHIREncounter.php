<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRDuration;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRVirtualServiceDetail;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDateTime;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Encounter
 *
 * @description An interaction between a patient and healthcare provider(s) for the purpose of providing healthcare service(s) or assessing the health status of a patient.  Encounter is primarily used to record information about the actual activities that occurred, where Appointment is used to record planned activities.
 */
#[FhirResource(type: 'Encounter', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/Encounter', fhirVersion: 'R5')]
class FHIREncounter extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Identifier(s) by which this encounter is known */
        public array $identifier = [],
        /** @var FHIREncounterStatusType|null status planned | in-progress | on-hold | discharged | completed | cancelled | discontinued | entered-in-error | unknown */
        #[NotBlank]
        public ?FHIREncounterStatusType $status = null,
        /** @var array<FHIRCodeableConcept> class Classification of patient encounter context - e.g. Inpatient, outpatient */
        public array $class = [],
        /** @var FHIRCodeableConcept|null priority Indicates the urgency of the encounter */
        public ?FHIRCodeableConcept $priority = null,
        /** @var array<FHIRCodeableConcept> type Specific type of encounter (e.g. e-mail consultation, surgical day-care, ...) */
        public array $type = [],
        /** @var array<FHIRCodeableReference> serviceType Specific type of service */
        public array $serviceType = [],
        /** @var FHIRReference|null subject The patient or group related to this encounter */
        public ?FHIRReference $subject = null,
        /** @var FHIRCodeableConcept|null subjectStatus The current status of the subject in relation to the Encounter */
        public ?FHIRCodeableConcept $subjectStatus = null,
        /** @var array<FHIRReference> episodeOfCare Episode(s) of care that this encounter should be recorded against */
        public array $episodeOfCare = [],
        /** @var array<FHIRReference> basedOn The request that initiated this encounter */
        public array $basedOn = [],
        /** @var array<FHIRReference> careTeam The group(s) that are allocated to participate in this encounter */
        public array $careTeam = [],
        /** @var FHIRReference|null partOf Another Encounter this encounter is part of */
        public ?FHIRReference $partOf = null,
        /** @var FHIRReference|null serviceProvider The organization (facility) responsible for this encounter */
        public ?FHIRReference $serviceProvider = null,
        /** @var array<FHIREncounterParticipant> participant List of participants involved in the encounter */
        public array $participant = [],
        /** @var array<FHIRReference> appointment The appointment that scheduled this encounter */
        public array $appointment = [],
        /** @var array<FHIRVirtualServiceDetail> virtualService Connection details of a virtual service (e.g. conference call) */
        public array $virtualService = [],
        /** @var FHIRPeriod|null actualPeriod The actual start and end time of the encounter */
        public ?FHIRPeriod $actualPeriod = null,
        /** @var FHIRDateTime|null plannedStartDate The planned start date/time (or admission date) of the encounter */
        public ?FHIRDateTime $plannedStartDate = null,
        /** @var FHIRDateTime|null plannedEndDate The planned end date/time (or discharge date) of the encounter */
        public ?FHIRDateTime $plannedEndDate = null,
        /** @var FHIRDuration|null length Actual quantity of time the encounter lasted (less time absent) */
        public ?FHIRDuration $length = null,
        /** @var array<FHIREncounterReason> reason The list of medical reasons that are expected to be addressed during the episode of care */
        public array $reason = [],
        /** @var array<FHIREncounterDiagnosis> diagnosis The list of diagnosis relevant to this encounter */
        public array $diagnosis = [],
        /** @var array<FHIRReference> account The set of accounts that may be used for billing for this Encounter */
        public array $account = [],
        /** @var array<FHIRCodeableConcept> dietPreference Diet preferences reported by the patient */
        public array $dietPreference = [],
        /** @var array<FHIRCodeableConcept> specialArrangement Wheelchair, translator, stretcher, etc */
        public array $specialArrangement = [],
        /** @var array<FHIRCodeableConcept> specialCourtesy Special courtesies (VIP, board member) */
        public array $specialCourtesy = [],
        /** @var FHIREncounterAdmission|null admission Details about the admission to a healthcare service */
        public ?FHIREncounterAdmission $admission = null,
        /** @var array<FHIREncounterLocation> location List of locations where the patient has been */
        public array $location = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
