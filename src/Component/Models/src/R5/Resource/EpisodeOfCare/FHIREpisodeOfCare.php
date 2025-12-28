<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/EpisodeOfCare
 *
 * @description An association between a patient and an organization / healthcare provider(s) during which time encounters may occur. The managing organization assumes a level of responsibility for the patient during this time.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'EpisodeOfCare', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/EpisodeOfCare', fhirVersion: 'R5')]
class FHIREpisodeOfCare extends FHIRDomainResource
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
        /** @var array<FHIRIdentifier> identifier Business Identifier(s) relevant for this EpisodeOfCare */
        public array $identifier = [],
        /** @var FHIREpisodeOfCareStatusType|null status planned | waitlist | active | onhold | finished | cancelled | entered-in-error */
        #[NotBlank]
        public ?\FHIREpisodeOfCareStatusType $status = null,
        /** @var array<FHIREpisodeOfCareStatusHistory> statusHistory Past list of status codes (the current status may be included to cover the start date of the status) */
        public array $statusHistory = [],
        /** @var array<FHIRCodeableConcept> type Type/class  - e.g. specialist referral, disease management */
        public array $type = [],
        /** @var array<FHIREpisodeOfCareReason> reason The list of medical reasons that are expected to be addressed during the episode of care */
        public array $reason = [],
        /** @var array<FHIREpisodeOfCareDiagnosis> diagnosis The list of medical conditions that were addressed during the episode of care */
        public array $diagnosis = [],
        /** @var FHIRReference|null patient The patient who is the focus of this episode of care */
        #[NotBlank]
        public ?\FHIRReference $patient = null,
        /** @var FHIRReference|null managingOrganization Organization that assumes responsibility for care coordination */
        public ?\FHIRReference $managingOrganization = null,
        /** @var FHIRPeriod|null period Interval during responsibility is assumed */
        public ?\FHIRPeriod $period = null,
        /** @var array<FHIRReference> referralRequest Originating Referral Request(s) */
        public array $referralRequest = [],
        /** @var FHIRReference|null careManager Care manager/care coordinator for the patient */
        public ?\FHIRReference $careManager = null,
        /** @var array<FHIRReference> careTeam Other practitioners facilitating this episode of care */
        public array $careTeam = [],
        /** @var array<FHIRReference> account The set of accounts that may be used for billing for this EpisodeOfCare */
        public array $account = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
