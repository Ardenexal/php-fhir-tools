<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/EncounterHistory
 *
 * @description A record of significant events/milestones key data throughout the history of an Encounter, often tracked for specific purposes such as billing.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
    type: 'EncounterHistory',
    version: '5.0.0',
    url: 'http://hl7.org/fhir/StructureDefinition/EncounterHistory',
    fhirVersion: 'R5',
)]
class FHIREncounterHistory extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?\FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?\FHIRUri $implicitRules = null,
        /** @var FHIRAllLanguagesType|null language Language of the resource content */
        public ?\FHIRAllLanguagesType $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?\FHIRNarrative $text = null,
        /** @var array<FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var FHIRReference|null encounter The Encounter associated with this set of historic values */
        public ?\FHIRReference $encounter = null,
        /** @var array<FHIRIdentifier> identifier Identifier(s) by which this encounter is known */
        public array $identifier = [],
        /** @var FHIREncounterStatusType|null status planned | in-progress | on-hold | discharged | completed | cancelled | discontinued | entered-in-error | unknown */
        #[NotBlank]
        public ?\FHIREncounterStatusType $status = null,
        /** @var FHIRCodeableConcept|null class Classification of patient encounter */
        #[NotBlank]
        public ?\FHIRCodeableConcept $class = null,
        /** @var array<FHIRCodeableConcept> type Specific type of encounter */
        public array $type = [],
        /** @var array<FHIRCodeableReference> serviceType Specific type of service */
        public array $serviceType = [],
        /** @var FHIRReference|null subject The patient or group related to this encounter */
        public ?\FHIRReference $subject = null,
        /** @var FHIRCodeableConcept|null subjectStatus The current status of the subject in relation to the Encounter */
        public ?\FHIRCodeableConcept $subjectStatus = null,
        /** @var FHIRPeriod|null actualPeriod The actual start and end time associated with this set of values associated with the encounter */
        public ?\FHIRPeriod $actualPeriod = null,
        /** @var FHIRDateTime|null plannedStartDate The planned start date/time (or admission date) of the encounter */
        public ?\FHIRDateTime $plannedStartDate = null,
        /** @var FHIRDateTime|null plannedEndDate The planned end date/time (or discharge date) of the encounter */
        public ?\FHIRDateTime $plannedEndDate = null,
        /** @var FHIRDuration|null length Actual quantity of time the encounter lasted (less time absent) */
        public ?\FHIRDuration $length = null,
        /** @var array<FHIREncounterHistoryLocation> location Location of the patient at this point in the encounter */
        public array $location = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
