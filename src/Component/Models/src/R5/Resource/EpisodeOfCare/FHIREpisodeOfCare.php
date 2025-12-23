<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 * @see http://hl7.org/fhir/StructureDefinition/EpisodeOfCare
 * @description An association between a patient and an organization / healthcare provider(s) during which time encounters may occur. The managing organization assumes a level of responsibility for the patient during this time.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'EpisodeOfCare', version: '5.0.0', url: 'http://hl7.org/fhir/StructureDefinition/EpisodeOfCare', fhirVersion: 'R5')]
class FHIREpisodeOfCare extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRAllLanguagesType language Language of the resource content */
		public ?FHIRAllLanguagesType $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRIdentifier> identifier Business Identifier(s) relevant for this EpisodeOfCare */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIREpisodeOfCareStatusType status planned | waitlist | active | onhold | finished | cancelled | entered-in-error */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIREpisodeOfCareStatusType $status = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIREpisodeOfCareStatusHistory> statusHistory Past list of status codes (the current status may be included to cover the start date of the status) */
		public array $statusHistory = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> type Type/class  - e.g. specialist referral, disease management */
		public array $type = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIREpisodeOfCareReason> reason The list of medical reasons that are expected to be addressed during the episode of care */
		public array $reason = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIREpisodeOfCareDiagnosis> diagnosis The list of medical conditions that were addressed during the episode of care */
		public array $diagnosis = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference patient The patient who is the focus of this episode of care */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?FHIRReference $patient = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference managingOrganization Organization that assumes responsibility for care coordination */
		public ?FHIRReference $managingOrganization = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod period Interval during responsibility is assumed */
		public ?FHIRPeriod $period = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> referralRequest Originating Referral Request(s) */
		public array $referralRequest = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference careManager Care manager/care coordinator for the patient */
		public ?FHIRReference $careManager = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> careTeam Other practitioners facilitating this episode of care */
		public array $careTeam = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> account The set of accounts that may be used for billing for this EpisodeOfCare */
		public array $account = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}
