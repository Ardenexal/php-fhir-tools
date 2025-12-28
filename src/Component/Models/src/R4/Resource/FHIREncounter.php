<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCoding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRDuration;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/Encounter
 *
 * @description An interaction between a patient and healthcare provider(s) for the purpose of providing healthcare service(s) or assessing the health status of a patient.
 */
#[FhirResource(type: 'Encounter', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Encounter', fhirVersion: 'R4')]
class FHIREncounter extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Identifier(s) by which this encounter is known */
        public array $identifier = [],
        /** @var FHIREncounterStatusType|null status planned | arrived | triaged | in-progress | onleave | finished | cancelled + */
        #[NotBlank]
        public ?FHIREncounterStatusType $status = null,
        /** @var array<FHIREncounterStatusHistory> statusHistory List of past encounter statuses */
        public array $statusHistory = [],
        /** @var FHIRCoding|null class Classification of patient encounter */
        #[NotBlank]
        public ?FHIRCoding $class = null,
        /** @var array<FHIREncounterClassHistory> classHistory List of past encounter classes */
        public array $classHistory = [],
        /** @var array<FHIRCodeableConcept> type Specific type of encounter */
        public array $type = [],
        /** @var FHIRCodeableConcept|null serviceType Specific type of service */
        public ?FHIRCodeableConcept $serviceType = null,
        /** @var FHIRCodeableConcept|null priority Indicates the urgency of the encounter */
        public ?FHIRCodeableConcept $priority = null,
        /** @var FHIRReference|null subject The patient or group present at the encounter */
        public ?FHIRReference $subject = null,
        /** @var array<FHIRReference> episodeOfCare Episode(s) of care that this encounter should be recorded against */
        public array $episodeOfCare = [],
        /** @var array<FHIRReference> basedOn The ServiceRequest that initiated this encounter */
        public array $basedOn = [],
        /** @var array<FHIREncounterParticipant> participant List of participants involved in the encounter */
        public array $participant = [],
        /** @var array<FHIRReference> appointment The appointment that scheduled this encounter */
        public array $appointment = [],
        /** @var FHIRPeriod|null period The start and end time of the encounter */
        public ?FHIRPeriod $period = null,
        /** @var FHIRDuration|null length Quantity of time the encounter lasted (less time absent) */
        public ?FHIRDuration $length = null,
        /** @var array<FHIRCodeableConcept> reasonCode Coded reason the encounter takes place */
        public array $reasonCode = [],
        /** @var array<FHIRReference> reasonReference Reason the encounter takes place (reference) */
        public array $reasonReference = [],
        /** @var array<FHIREncounterDiagnosis> diagnosis The list of diagnosis relevant to this encounter */
        public array $diagnosis = [],
        /** @var array<FHIRReference> account The set of accounts that may be used for billing for this Encounter */
        public array $account = [],
        /** @var FHIREncounterHospitalization|null hospitalization Details about the admission to a healthcare service */
        public ?FHIREncounterHospitalization $hospitalization = null,
        /** @var array<FHIREncounterLocation> location List of locations where the patient has been */
        public array $location = [],
        /** @var FHIRReference|null serviceProvider The organization (facility) responsible for this encounter */
        public ?FHIRReference $serviceProvider = null,
        /** @var FHIRReference|null partOf Another Encounter this encounter is part of */
        public ?FHIRReference $partOf = null,
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
