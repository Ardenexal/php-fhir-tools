<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIREpisodeOfCareStatusType;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRIdentifier;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRPeriod;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author Health Level Seven International (Patient Administration)
 *
 * @see http://hl7.org/fhir/StructureDefinition/EpisodeOfCare
 *
 * @description An association between a patient and an organization / healthcare provider(s) during which time encounters may occur. The managing organization assumes a level of responsibility for the patient during this time.
 */
#[FhirResource(
    type: 'EpisodeOfCare',
    version: '4.3.0',
    url: 'http://hl7.org/fhir/StructureDefinition/EpisodeOfCare',
    fhirVersion: 'R4B',
)]
class FHIREpisodeOfCare extends FHIRDomainResource
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
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRIdentifier> identifier Business Identifier(s) relevant for this EpisodeOfCare */
        public array $identifier = [],
        /** @var FHIREpisodeOfCareStatusType|null status planned | waitlist | active | onhold | finished | cancelled | entered-in-error */
        #[NotBlank]
        public ?FHIREpisodeOfCareStatusType $status = null,
        /** @var array<FHIREpisodeOfCareStatusHistory> statusHistory Past list of status codes (the current status may be included to cover the start date of the status) */
        public array $statusHistory = [],
        /** @var array<FHIRCodeableConcept> type Type/class  - e.g. specialist referral, disease management */
        public array $type = [],
        /** @var array<FHIREpisodeOfCareDiagnosis> diagnosis The list of diagnosis relevant to this episode of care */
        public array $diagnosis = [],
        /** @var FHIRReference|null patient The patient who is the focus of this episode of care */
        #[NotBlank]
        public ?FHIRReference $patient = null,
        /** @var FHIRReference|null managingOrganization Organization that assumes care */
        public ?FHIRReference $managingOrganization = null,
        /** @var FHIRPeriod|null period Interval during responsibility is assumed */
        public ?FHIRPeriod $period = null,
        /** @var array<FHIRReference> referralRequest Originating Referral Request(s) */
        public array $referralRequest = [],
        /** @var FHIRReference|null careManager Care manager/care coordinator for the patient */
        public ?FHIRReference $careManager = null,
        /** @var array<FHIRReference> team Other practitioners facilitating this episode of care */
        public array $team = [],
        /** @var array<FHIRReference> account The set of accounts that may be used for billing for this EpisodeOfCare */
        public array $account = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}
